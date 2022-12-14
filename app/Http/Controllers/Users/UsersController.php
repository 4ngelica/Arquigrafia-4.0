<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use App\lib\log\EventLogger;
use App\lib\utils\HelpTool;
use Carbon\Carbon;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookAuthorizationException;
use Facebook\FacebookRequestException;
use App\Models\Institutions\Institution;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Gamification\Gamified;
use Illuminate\Support\Facades\Config;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Users\User;
use App\Models\Photos\Photo;
use File;
use Illuminate\Support\Facades\Hash;
use Redirect;
use App\Models\Users\Occupation;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Event;
use App\Models\Collaborative\Follow;
use Illuminate\Support\Facades\Redis;
use Cache;

class UsersController extends Controller {

  public function __construct()
  {
    // $this->beforeFilter('auth',
    //   array('only' => ['follow', 'unfollow']));
  }

  public function index()
  {
    $users = User::all();

    return view('/users/index',['users' => $users]);
  }


  public function show($id)
  {

      $user = User::find($id);
      $photos = $user->photos;
      $albums = $user->albums;
      $evaluations = $user->evaluations;

    $following = Follow::raw((function($collection) use ($id) {
        return $collection->aggregate([
         [
           '$match' => [
             'following_id' => $id,
             'followed_id' => [
               '$exists'=> true
             ],
           ]
         ],
        ]);
    }));

    $followers = Follow::raw((function($collection) use ($id) {
        return $collection->aggregate([
         [
           '$match' => [
             'followed_id' => $id,
             'following_id' => [
               '$exists'=> true
             ],
           ]
         ],
        ]);
    }));

    $followersNumber = count($followers);
    $followingNumber = count($following);

    if(Auth::user() && $user->id !== Auth::user()->id){
      $authUser = Auth::user()->id;
      $isFollowing = Follow::raw((function($collection) use ($id, $authUser) {
          return $collection->aggregate([
           [
             '$match' => [
               'followed_id' => $id,
               'following_id' => $authUser
             ]
           ],
          ]);
      }));
      $isFollowing = count($isFollowing);
    }else{
      $isFollowing = 0;
    }

    return view('new_front.users.show', compact(['user', 'photos', 'albums', 'evaluations', 'followingNumber', 'followersNumber', 'isFollowing']));

  }

  // show create account form
  public function account()
  {
    if (Auth::check()) return Redirect::to('/home');
    return view('/modal/account');
  }


  // register of user with email
  public function store(Request $request)
  {
    $request->flash();
    $input = $request->all();

    $rules = array(
        'name' => 'required',
        'login' => 'required|regex:/^[a-z0-9-_]{3,20}$/|unique:users',
        'password' => 'required|min:6|confirmed',
        'email' => 'required|email|unique:users',
        'terms' => 'required'
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
      $messages = $validator->messages();
      return Redirect::to('/users/account')->withErrors($messages);
    } else {

       $name = $input["name"];
       $email =$input["email"];
       $login =$input["login"];
       $verify_code = Str::random(30);

      $user = User::create([
      'name' => $name,
      'email' => $email,
      'password' => Hash::make($input["password"]),
      'login' => $login,
      'verify_code' => $verify_code
      ]);

      $user->update(['id' => $user->_id]);

      EventLogger::printEventLogs(null, "new_account", ["origin" => "Arquigrafia"], "Web");

       Mail::send('emails.users.verify', array('name' => $name, 'email' => $email, 'login' => $login ,'verifyCode' => $verify_code),
          function($msg) use($email) {
            $msg->to($email)
                ->subject('[Arquigrafia]- Cadastro de Usu??rio');
        });

        return Redirect::to("/users/register");

    }
  }

  public function emailRegister(){

    $msgType = "sendEmail";
    return view('/modal/register')->with(['msgType'=>$msgType]);


  }

  public function verify($verify_code){

    if(!empty($verify_code)){
      $newUser= User::userVerifyCode($verify_code);
    }

    if (!$newUser){
            return Redirect::to('users/verify');
      }else{
          $newUser->active = 'yes';
          $newUser->verify_code = null;
          $newUser->save();

          return Redirect::to('/users/login')->with('msgRegister', "<strong>Conta ativada com sucesso!</strong>");

      }
  }

  public function verifyError(){

      $msgType = "verify";
      return view('/modal/register')->with(['msgType'=>$msgType]);
  }


  public function forgetForm()
  {
    $message = false;
    $existEmail = true;
    return view('/modal/forget')->with(['message'=>$message, 'existEmail'=>$existEmail]);
  }

  public function forget(Request $request){
    $input = $request->all();
    $email = $input["email"];
    $rules = array('email' => 'required|email');

    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
      $messages = $validator->messages();
      return Redirect::to('/users/forget')->withErrors($messages);
    }else{

      $user = User::userInformationObtain($email);

      if(!empty($user)){
        $randomPassword = strtolower(Str::quickRandom(8));
        $user->password = Hash::make($randomPassword);
        $user->oldAccount = 0; // usu??rio j?? tem senha nova
        $user->touch(); // touch j?? salva
        dd(\Config::get("mail.username"));
        Mail::send('emails.users.reset-password', array('user' => $user,'email' => $email,'randomPassword' => $randomPassword),
         function($message) use($email) {
               $message->to($email)
               ->subject('[Arquigrafia] - Esqueci minha senha');
         });

        $message = true;
        $existEmail = true;
      } else{
        $existEmail = false;
        $message = null;
      }
      return view('/modal/forget')->with(['message'=>$message,'email'=>$email, 'existEmail'=>$existEmail]);
    }
  }


  public function loginForm()
  {
    if (Auth::check())
        return Redirect::to('/home');

    return view('/modal/login');
  }

  public function login(Request $request)
  {
    $input = $request->all();
    $user = User::userInformation($input["login"]);
    if (isset($user)) {
      $user = $user->first();
      $integration_message = $this->integrateAccounts($user->email);
    }
    if ($user != null && $user->oldAccount == 1)
    {
      if ( User::checkOldAccount($user, $input["password"]) )
      {
        $user->updateAccount($input['password']);
      } else {
        Session::put('login.message', 'Usu??rio e/ou senha inv??lidos, tente novamente.');
        return Redirect::to('/users/login')->withInput();
      }
    }
    if (isset($user)) {
      if ($user->active == 'no' && (Auth::validate(array('login' => $user->login, 'password' => $input["password"])) == true)) {
        Session::put('login.message', 'Finalize seu cadastro acessando o link enviado ao seu e-mail.');
            return Redirect::to('/users/login')->withInput();
      }
      if ( Auth::attempt(array('login' => $user->login, 'password' => $input["password"],'active' => 'yes')) == true ||
          Auth::attempt(array('email' => $input["login"], 'password' => $input["password"],'active' => 'yes')) == true  ) {
        if ( Session::has('filter.login') ) //acionado pelo login
        {
          Session::forget('filter.login');
          EventLogger::printEventLogs(null, 'login', ["origin" => "Arquigrafia"], 'Web');
          if (isset($integration_message)) {
            return Redirect::to('/home')->with('msgWelcome', $integration_message);
          }
          return Redirect::intended('/home');
        }
        if ( Session::has('url.previous') )
        {
          $url = Session::pull('url.previous');

          if (!empty($url)) {
            EventLogger::printEventLogs(null, 'login', ["origin" => "Arquigrafia"], 'Web');

            if($url == URL::to('users/forget')){
              return Redirect::to('/home');
            }elseif(!empty($input["firstTime"])){
                return Redirect::to('/home')->with('msgWelcome', "Bem-vind@ ".ucfirst($user->name).".");

            }else{
              if($url == URL::to("/")."/" || strpos($url, '/landing') !== false)
                  return Redirect::to('/home');
              else
                  return Redirect::to($url);
            }

          }


          EventLogger::printEventLogs(null, 'login', ["origin" => "Arquigrafia"], 'Web');
          if (isset($integration_message)) {
            return Redirect::to('/home')->with('msgWelcome', $integration_message);
          }
          return Redirect::to('/home');
        }
        EventLogger::printEventLogs(null, 'login', ["origin" => "Arquigrafia"], 'Web');
        if (isset($integration_message)) {
          return Redirect::to('/home')->with('msgWelcome', $integration_message);
        }
        return Redirect::to('/home');
      } else {
  			Session::put('login.message', 'Usu??rio e/ou senha inv??lidos, tente novamente.');
        return Redirect::to('/users/login')->withInput();
      }
    } else {
      Session::put('login.message', 'Usu??rio e/ou senha inv??lidos, tente novamente.');
      return Redirect::to('/users/login')->withInput();
    }
  }

  public function logout()
  {
    if (Auth::check()) {
      EventLogger::printEventLogs(null, 'logout', null, 'Web');

      $variationId = Gamified::getGamifiedVariationId();
      Auth::logout();
      Session::flush();
      Gamified::saveGamifiedVariationId($variationId);

      return Redirect::to('/home');
    }
    return Redirect::to('/home');
  }

  public function facebook()
  {
    $fb_config = Config::get('facebook');
    $facebook = new Facebook( $fb_config );

    $params = array(
        'redirect_uri' => url('/users/login/fb/callback'),
        'scope' => 'email',
    );
    return Redirect::to($facebook->getLoginUrl($params));
  }

    public function callback()
   {
    session_start();

    $fb_config = Config::get('facebook');

    FacebookSession::setDefaultApplication($fb_config["id"], $fb_config["secret"]);

    $helper = new FacebookRedirectLoginHelper(url('/users/login/fb/callback'));

    try {
      $session = $helper->getSessionFromRedirect();
    } catch(FacebookRequestException $ex) {
      dd($ex);
    } catch(\Exception $ex) {
      dd($ex);
    }
    if ($session) {
      $request = new FacebookRequest($session, 'GET', '/me');
      $response = $request->execute();
      $fbuser = $response->getGraphObject();
      $fbid = $fbuser->getProperty('id');
      $fbmail = $fbuser->getProperty('email');

      $integration_message = $this->integrateAccounts($fbmail);
      $user = User::where('id_facebook', '=', $fbid)->orWhere('login', '=', $fbid)->first();

      if (!is_null($user)) {
        Auth::loginUsingId($user->id);
        if(is_null($user->id_facebook)) {
          $user->id_facebook = $fbid;
          $user->save();
        }
        $request = new FacebookRequest(
          $session,
          'GET',
          '/me/picture',
          array (
            'redirect' => false,
            'height' => '200',
            'type' => 'normal',
            'width' => '200',
          )
        );
        $response = $request->execute();
        $pic = $response->getGraphObject();
        $image = Image::make($pic->getProperty('url'))->save(public_path().'/arquigrafia-avatars/'.$user->id.'.jpg');
        if ($user->photo != '/arquigrafia-avatars/'.$user->id.'.jpg') {
          $user->photo = '/arquigrafia-avatars/'.$user->id.'.jpg';
          $user->save();
        }

        EventLogger::printEventLogs(null, "login", ["origin" => "Facebook"], "Web");
        if (isset($integration_message)) {
          return Redirect::to('/home')->with('msgWelcome', $integration_message);
        }
        return Redirect::to('/home')->with('msgWelcome', "Bem-vindo {$user->name}!");

      } else {
        $query = User::where('email', '=', $fbmail)->first();
        if (!is_null($query)) {
          $query->id_facebook = $fbid;
          $query->save();
          Auth::loginUsingId($query->id);
          EventLogger::printEventLogs(null, "login", ["origin" => "Facebook"], "Web");
          if (isset($integration_message)) {
            return Redirect::to('/home')->with('msgWelcome', $integration_message);
          }
          return Redirect::to('/home')->with('msgWelcome', "Bem-vindo {$query->name}!");
        }
        else {
        $user = new User;
        $user->name = $fbuser->getProperty('name');
        $user->login = $fbuser->getProperty('id');
        $user->email = $fbuser->getProperty('email');
        $user->password = 'facebook';
        $user->id_facebook = $fbuser->getProperty('id');
        $user->save();
        Auth::loginUsingId($user->id);

        $request = new FacebookRequest(
          $session,
          'GET',
          '/me/picture',
          array (
            'redirect' => false,
            'height' => '200',
            'type' => 'normal',
            'width' => '200',
          )
        );
        $response = $request->execute();
        $pic = $response->getGraphObject();
        $image = Image::make($pic->getProperty('url'))->save(public_path().'/arquigrafia-avatars/'.$user->id.'.jpg');
        $user->photo = '/arquigrafia-avatars/'.$user->id.'.jpg';
        $user->save();

        EventLogger::printEventLogs(null, "new_account", ["origin" => "Facebook"], "Web");

        return Redirect::to('/home')->with('message', 'Sua conta foi criada com sucesso!');
      }
      }

    }
  }

  public function getFacebookPicture() {
    if (Auth::check()) {
      $user = Auth::user();
      if ($user->id_facebook != null) {
        $image = file_get_contents('https://graph.facebook.com/'.$user->id_facebook.'/picture?type=large');
        $file_name = public_path().'/arquigrafia-avatars/'.$user->id.'.jpg';
        $file = fopen($file_name, 'w+');
        fputs($file, $image);
        fclose($file);
        $user->photo = '/arquigrafia-avatars/'.$user->id.'.jpg';
        $user->save();

      }
    }
    return $user->photo;
  }

  public function follow($user_id)
  {
    $logged_user = Auth::user();

    if ($logged_user == null)
       return Redirect::to('/home');

    $following = $logged_user->following;



    if ($user_id != $logged_user->id && !$following->contains($user_id)) {

      $logged_user->following()->attach($user_id);

      EventLogger::printEventLogs(null, 'follow', ['target_userId' => $user_id], 'Web');
    }

    return Redirect::to(URL::previous());
  }

  public function unfollow($user_id)
  {
    $logged_user = Auth::user();

    if ($logged_user == null)
      return Redirect::to('/home');

    $following = $logged_user->following;


    if ($user_id != $logged_user->id && $following->contains($user_id)) {
      $logged_user->following()->detach($user_id);

      EventLogger::printEventLogs(null, 'unfollow', ['target_userId' => $user_id], 'Web');
    }

    return Redirect::to(URL::previous());
  }

  public function profile($id)
  {
    $path = public_path().'/arquigrafia-avatars/'.$id.'_view.jpg';
    if( File::exists($path) ) {
      header("Cache-Control: public");
      header("Content-Disposition: inline; filename=\"".$id . '_view.jpg'."\"");
      header("Content-Type: image/jpg");
      header("Content-Transfer-Encoding: binary");
      readfile($path);
      exit;
    }
    return $path;
  }

/**
 * Show the form for editing the specified resource.
 *
 * @return Response
 */
  public function edit($id) {
    if (Session::has('institutionId') ) {
      return Redirect::to('/home');
    }

    $user = User::find($id);

    $logged_user = Auth::User();
    if ($logged_user == null) {
      return Redirect::action('App\Http\Controllers\Photos\PagesController@home');
    }
    elseif ($logged_user->id == $user->id) {
      return view('users.edit')->with( ['user' => $user] );
    }
    return Redirect::action('App\Http\Controllers\Photos\PagesController@home');
  }

  public function update(Request $request, $id) {
    $user = User::find($id);

    $input =  $request->only('name', 'login', 'email', 'scholarity', 'lastName', 'site', 'birthday', 'country', 'state', 'city',
      'photo', 'gender', 'institution', 'occupation', 'visibleBirthday', 'visibleEmail','old_password','user_password','user_password_confirmation');

    $rules = array(
        'name' => 'required',
        'login' => 'required',
        'email' => 'required|email',
        'user_password' => 'nullable|min:6|regex:/^[a-z0-9-@_]{6,10}$/|confirmed',
        'birthday' => 'nullable|date|date_format:"d/m/Y"',
        'photo' => 'max:2048|mimes:jpeg,jpg,png,gif'
    );
    if ($input['email'] !== $user->email)
      $rules['email'] = 'required|email|unique:users';

    if ($input['login'] !== $user->login)
      $rules['login'] = 'required|unique:users';

    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
      $messages = $validator->messages();
      return Redirect::to('/users/' . $id . '/edit')->withErrors($messages);
    } else {
      $user->name = $input['name'];
      $user->login = $input['login'];
      $user->email = $input['email'];
      $user->scholarity = $input['scholarity'];
      $user->lastName = $input['lastName'];
      $user->site = $input['site'];
      if ( !empty($input["birthday"]) )
        $user->birthday = $input["birthday"];
      $user->country = $input['country'];
      $user->state = $input['state'];
      $user->city = $input['city'];
      $user->gender = $input['gender'] ?? null;
      $user->visibleBirthday = $input['visibleBirthday'] ?? 'no';
      $user->visibleEmail = $input['visibleEmail'] ?? 'no';

      Log::info("check=".Hash::check($input["old_password"], $user->password)."autenticar =".Auth::attempt(array('login' => $user->login,'password' => $input["old_password"])));

      if(Hash::check($input["old_password"], $user->password)){

            if(!empty($input['user_password']) || trim($input['user_password']) != ""){
                $user->password = Hash::make($input["user_password"]);
            }else{
                  $messages = array('user_password'=>array('Inserir uma senha v??lida com m??nimo 6 caracteres'));
                  return Redirect::to('/users/' . $id . '/edit')->withErrors($messages);
            }
       } else if(!empty($input['old_password']) || trim($input['old_password']) != ""){
            $messages = array('old_password'=>array('Antiga senha incorreta'));
            return Redirect::to('/users/' . $id . '/edit')->withErrors($messages);
       } else if(!empty($input['user_password']) || trim($input['user_password']) != "" ){
            $messages = array('old_password'=>array('Precisa inserir a senha antiga'));
            return Redirect::to('/users/' . $id . '/edit')->withErrors($messages);
       }


      if ($input["institution"] != null or $input["occupation"] != null) {
        $occupation = Occupation::firstOrCreate(['user_id'=>$user->id]);
        $occupation->institution = $input["institution"];
        $occupation->occupation = $input["occupation"];
        $occupation->save();
      }


      if ( $request->hasFile('photo') and  $request->file('photo')->isValid())  {
        $file =  $request->file('photo');
        $ext = $file->getClientOriginalExtension();

        $user->photo = "/arquigrafia-avatars/".$user->id.".jpg";
        $image = Image::make( $request->file('photo'))->encode('jpg', 80);

        $image->save(public_path().'/arquigrafia-avatars/'.$user->id.'.jpg');
        $file->move(public_path().'/arquigrafia-avatars', $user->id."_original.".strtolower($ext));
      }
      $user->touch();
      $user->save();

      return Redirect::to("/users/{$user->id}")->with('message', '<strong>Edi????o de perfil do usu??rio</strong><br>Dados alterados com sucesso');

    }
  }

  public function stoaLogin() {

    $account = Input::get('stoa_account');
    $password = Input::get('password');
    $stoa_user = $this->getStoaAccount($account, $password, 'login');
    if (!$stoa_user->ok) {
      $stoa_user = $this->getStoaAccount($account, $password, 'usp_id');
      if (!$stoa_user->ok) {
        return Response::json(false);
      }
    }
    $user = User::stoa($stoa_user);
    Auth::loginUsingId($user->id);

    EventLogger::printEventLogs(null, "login", ["origin" => "Stoa"], "Web");
    return Response::json(true);
  }

  public function institutionalLogin(Request $request) {
    Log::info("Login Institution");
    $login = $request->get('login');
    $institutionId = $request->get('institution');
    $password = $request->get('password');
    Log::info("Retrieved params login=".$login.", institution=".$institutionId);
    $booleanExist = User::userBelongInstitution($login,$institutionId);
    Log::info("Result belong institution -> booleanExist=".$booleanExist);

    if ((Auth::attempt(array('login' => $login, 'password' => $password)) == true ||
        Auth::attempt(array('email' => $login, 'password' => $password,'active' => 'yes')) == true) &&
        $booleanExist == true){
        $displayedInstitutionName = null;
        $institution = Institution::find($institutionId);
        $displayedInstitutionName = HelpTool::formattingLongText($institution->name, $institution->acronym, 25);
        Log::info("Valid access, redirect");
        Session::put('institutionId', $institutionId);
        Session::put('displayInstitution', $displayedInstitutionName);
        return Redirect::to('/home');
    } else {
      Log::info("Invalid access, return message");
      return \Response::json(false);
    }
  }

  private function getStoaAccount($account, $password, $account_type) {
    $ch = curl_init();
    $this->setCurlOptions($ch, $account, $password, $account_type);
    $response = curl_exec($ch);
    curl_close ($ch);
    return json_decode($response);
  }

  private function setCurlOptions($ch, $account, $password, $account_type) {
    curl_setopt($ch, CURLOPT_URL,"https://social.stoa.usp.br/plugin/stoa/authenticate/");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
      http_build_query([
          $account_type => $account,
          'password' => $password,
          'fields' => 'full'
        ])
    );
  }

  private function integrateAccounts($email) {
    $all_acc = User::where('email','=',$email)->get();
    if (count($all_acc) <= 1) {
      return;
    }
    $arq_acc =  User::whereRaw('(email = ?) and (id_stoa is NULL or id_stoa != login) and (id_facebook is NULL or id_facebook != login)', array($email))->first();
    $fb_acc = User::whereRaw('(email = ?) and (id_facebook = login)', array($email))->first();
    $stoa_acc = User::whereRaw('(email = ?) and (id_stoa = login)', array($email))->first();
    if (!is_null($arq_acc)) {
      if ($arq_acc->photo == "/arquigrafia-avatars/" . $arq_acc->id . ".jpg") {
        $has_photo = true;
      }
      if (!is_null($fb_acc)) {
        $fb_boolean = true;
        DB::table('users')->where('id', '=', $arq_acc->id)->update(array('id_facebook' => $fb_acc->id));
        if (!isset($has_photo)) {
          if ($fb_acc->photo == "/arquigrafia-avatars/" . $fb_acc->id . ".jpg") {
            $old_filename = public_path() . $fb_acc->photo;
            $new_filename = public_path() . "/arquigrafia-avatars/" . $arq_acc->id . ".jpg";
            if (File::exists($old_filename) && File::move($old_filename, $new_filename)) {
              $arq_acc->photo = "/arquigrafia-avatars/" . $arq_acc->id . ".jpg";
              $has_photo = true;
            }
          }
        }
        $this->getAttributesFromTo($fb_acc, $arq_acc);
      }
      $result = "Sua(s) conta(s): ";
      if (isset($fb_boolean)) {
        $result = $result . "Facebook";
      }
      if (isset($fb_boolean) && isset($stoa_boolean)) {
        $result = $result . " e ";
      }
      if (isset($stoa_boolean)) {
        $result = $result . "Stoa";
      }
      $result = $result . " foi(ram) integrada(s) ?? sua conta Arquigrafia";
      if (isset($fb_boolean)) {
        DB::table('users')->where('id', '=', $fb_acc->id)->delete();
      }
      if (isset($stoa_boolean)) {
        DB::table('users')->where('id', '=', $stoa_acc->id)->delete();
      }
      return $result;
    }
  }

  private function getAttributesFromTo($accountFrom, $accountTo) {
    DB::table('friendship')->where('following_id', '=', $accountFrom->id)->update(array('following_id' => $accountTo->id));
    DB::table('friendship')->where('followed_id', '=', $accountFrom->id)->update(array('followed_id' => $accountTo->id));
    DB::table('photos')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('binomial_evaluation')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('comments')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('albums')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('notifications')->where('sender_id', '=', $accountFrom->id)->update(array('sender_id' => $accountTo->id));
    DB::table('notification_user')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('likes')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('occupations')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('users_roles')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('user_badges')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('employees')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('friendship_institution')->where('following_user_id', '=', $accountFrom->id)
      ->update(array('following_user_id' => $accountTo->id));
  }

   public function usersList()
  {
        if (Auth::check()) {
            echo "Criando o arquivo Json com a lista de usuarios";

            $pathFile = public_path('data')."/users.json";
            $usersAll = User::userDataToJson();
            $users = $usersAll->toJson();

            File::put($pathFile, $users);
        }else{
          dd("Precisa estar logado");

        }
  }

  public function updateUsersListJson($user){
      $pathFile = public_path('data')."/users.json";

      if (File::exists($pathFile)){
                  Log::info("Adicionando texto no arquivo json de usuario");

                  $search = ']';
                  $replace = ',{"id":'.$user->id.', "name": "'.$user->name.'"}]';
                  file_put_contents($pathFile, str_replace($search, $replace, file_get_contents($pathFile)));
      }
  }


}
