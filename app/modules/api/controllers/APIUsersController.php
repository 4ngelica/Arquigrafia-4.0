<?php
namespace modules\api\controllers;
use lib\log\EventLogger;

class APIUsersController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return \Response::json(\User::all()->toArray());
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
			$user = \User::create(['name' => $input["name"],
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
	public function show($name)
	{
		$user = \User::where("login", "=", $name)->first();
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
	public function update($id)
	{
		//
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
