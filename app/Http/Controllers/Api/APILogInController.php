<?php

namespace App\Http\Controllers\Api;

use lib\log\EventLogger;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users\User;

class APILogInController extends Controller {

	public function verify_credentials(Request $request) {
		$input = $request->all();

		$credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

				if(Auth::attempt($credentials)){
					$user = User::where('email', $request['email'])->firstOrFail();
					$token = $user->createToken('auth_token')->plainTextToken;

					return \Response::json(['token' => $token], 200);
				}

				return \Response::json(['msg' => 'Usuário ou senha inválidos.'], 401);
	}

	public function verify_credentials_facebook() {
		$input = \Input::all();

    $fbid = $input["id_facebook"];
    $fbname = $input["name"];
    $fbmail = $input["email"];
    $fbImageURL = 'https://graph.facebook.com/'.$fbid.'/picture?width=200&height=200';

    $user = \User::where('id_facebook', '=', $fbid)->orWhere('login', '=', $fbid)->first();

    if (!is_null($user)) {
      \Auth::loginUsingId($user->id);
      if(is_null($user->id_facebook)) {
        $user->id_facebook = $fbid;
      }
      $user->mobile_token = \Hash::make(str_random(10));

      $image = \Image::make($fbImageURL)->save(public_path().'/arquigrafia-avatars/'.$user->id.'.jpg');
      if ($user->photo != '/arquigrafia-avatars/'.$user->id.'.jpg') {
        $user->photo = '/arquigrafia-avatars/'.$user->id.'.jpg';
      }

      $user->save();

      EventLogger::printEventLogs(null, "login", ["origin" => "Facebook"], "App");

      return \Response::json(['login' => $user->login, 'token' => $user->mobile_token, 'id' => $user->id, 'valid' => 'true', 'msg' => 'Login efetuado com sucesso.']);
    } else {
      $user = \User::where('email', '=', $fbmail)->first();
      if (!is_null($user)) {
        $user->id_facebook = $fbid;
        $user->mobile_token = \Hash::make(str_random(10));
        $user->save();
        \Auth::loginUsingId($user->id);
        EventLogger::printEventLogs(null, "login", ["origin" => "Facebook"], "App");

        return \Response::json(['login' => $user->login, 'token' => $user->mobile_token, 'id' => $user->id, 'valid' => 'true', 'msg' => 'Login efetuado com sucesso.']);
      } else {
        $user = new \User;
        $user->name = $fbname;
        $user->login = $fbid;
        $user->email = $fbmail;
        $user->password = 'facebook';
        $user->id_facebook = $fbid;
        $user->mobile_token = \Hash::make(str_random(10));
        $user->save();
        \Auth::loginUsingId($user->id);

        $image = \Image::make($fbImageURL)->save(public_path().'/arquigrafia-avatars/'.$user->id.'.jpg');
        if ($user->photo != '/arquigrafia-avatars/'.$user->id.'.jpg') {
          $user->photo = '/arquigrafia-avatars/'.$user->id.'.jpg';
          $user->save();
        }

        return \Response::json(['login' => $user->login, 'token' => $user->mobile_token, 'id' => $user->id, 'valid' => 'true', 'msg' => 'Login efetuado com sucesso.']);
      }

    }

		return \Response::json(['login' => '', 'token' => '', 'id' => '', 'valid' => 'false', 'msg' => 'Login com Facebook foi invalidado.']);
	}

	public function validate_mobile_token() {
		$input = \Input::all();
		$user = \User::where('login', '=', $input["login"])->first();
		if(!is_null($user)) {
			if($input["token"] == $user->mobile_token && $input["id"] == $user->id) {
				return \Response::json(['auth' => true]);
			}
		}
		return \Response::json(['auth' => false]);
	}

	public function log_out() {
		$input = \Input::all();
		$user = \User::where('login', '=', $input["login"])->first();
		if(!is_null($user)) {
			if($input["token"] == $user->mobile_token) {
				$user->mobile_token = null;
				$user->save();

				EventLogger::printEventLogs(null, 'logout', ['user' => $user->id], 'mobile');

				return \Response::json(['logged_out' => 'true']);
			}
		}
		return \Response::json(['logged_out' => 'false']);
	}
}
