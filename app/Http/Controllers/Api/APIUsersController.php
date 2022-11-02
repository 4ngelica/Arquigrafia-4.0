<?php

namespace App\Http\Controllers\Api;

use App\Models\Collaborative\Tag;

use lib\log\EventLogger;
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use App\Models\Collaborative\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class APIUsersController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return \Response::json(User::all()->toArray());
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//Validação do input
		$input = \Input::all();
		//$input = $input['data'];
		$rules = Array( 'name'     => 'required',
						'email'    => 'required|email|unique:users',
						'password' => 'required|min:6|',
						'login'    => 'required|regex:/^[a-z0-9-_]{3,20}$/|unique:users',
						'terms'    => 'required');
		$validator = \Validator::make($input, $rules);

		if($validator->fails()){
			$messages = $validator->messages();
      		return \Response::json(['valid' => 'false', 'errors' => $messages]);
		}
		else {
			$user = User::create(['name' => $input["name"],
      							  'email' => $input["email"],
      							  'password' => \Hash::make($input["password"]),
      							  'login' => $input["login"]
      							 ]);
			$user->mobile_token = \Hash::make(str_random(10));
			$user->active = 'yes';
          	$user->verify_code = null;
          	$user->save();
          	$email = $input["email"];

          	\Mail::send('emails.users.welcome', array('name' => $input["name"], 'email' => $input["email"], 'login' => $input["login"]),
          function($msg) use($email) {
            $msg->to($email)
                ->subject('[Arquigrafia]- Cadastro de Usuário');
        });

          	/* Registro de logs */
          	EventLogger::printEventLogs(null, "new_account", ["origin" => "Arquigrafia", "user" => $user->id], "mobile");

			return \Response::json(['login' => $input["login"], 'token' => $user->mobile_token, 'id' => $user->id, 'valid' => 'true', 'msg' => 'Cadastro efetuado com sucesso.']);
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::find($id);

		dd($user->followers);


		$teste = User::raw(function($collection)
		{
		    return $collection->aggregate([
		    [
		      '$match' => [
		        'to_id' => auth()->id()
		      ]
		    ],
        [
            '$group' => [
                '_id' => '$from_id',
                'messages_count' => [
                    '$sum' => 1
                ]
            ]
        ]
		   ]);
		});

		return \Response::json(array_merge($user->toArray(), ["followers" => count($user->followers), "following" => (count($user->following) + count($user->followingInstitution)), "photos" => count($user->photos)]));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 public function update(Request $request, $id)
 	{
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
			{
				$rules['email'] = 'required|email|unique:users';
			}

			if ($input['login'] !== $user->login)
			{
				$rules['login'] = 'required|unique:users';
			}

			$validator = Validator::make($input, $rules);

			foreach ($input as $key => $value) {
				if(!is_null($value) && $key !== 'old_password' && $key !== 'user_password' && $key !== 'user_password_confirmation'){
					$user->$key = $value;
				}
			}

			if(Hash::check($input["old_password"], $user->password)){

						if(!empty($input['user_password']) || trim($input['user_password']) != ""){
								$user->password = Hash::make($input["user_password"]);
						}else{
									$messages = array('user_password'=>array('Inserir uma senha válida com mínimo 6 caracteres'));
									return \Response::json($messages, 500);
						}
			 } else if(!empty($input['old_password']) || trim($input['old_password']) != ""){
						$messages = array('old_password'=>array('Antiga senha incorreta'));
						return \Response::json($messages, 500);
			 } else if(!empty($input['user_password']) || trim($input['user_password']) != "" ){
						$messages = array('old_password'=>array('Precisa inserir a senha antiga'));
						return \Response::json($messages, 500);
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

			return \Response::json($user, 200);
 	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
