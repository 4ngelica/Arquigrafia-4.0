<?php

namespace modules\collaborative\controllers;
use lib\log\EventLogger;
use Auth;

class FollowController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($user_id)
	{
		$logged_user = Auth::user();
    
	    if ($logged_user == null) //futuramente, adicionar filtro de login
	       return \Redirect::to('/home');

	    $following = $logged_user->following;

	    
	    
	    if ($user_id != $logged_user->id && !$following->contains($user_id)) {
	      //Envio da Notificação
	      $logged_user->following()->attach($user_id);

	      $logged_user_id = Auth::user()->id;

	      \Event::fire('user.followed', array($logged_user_id, $user_id));

	      $eventContent['target_userId'] = $user_id;
	      EventLogger::printEventLogs(null, 'follow', $eventContent, 'Web');
	    }

	    return \Redirect::to(\URL::previous()); // redirecionar para friends
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
	public function destroy($user_id)
	{
		$logged_user = Auth::user();
    
	    if ($logged_user == null) //futuramente, adicionar filtro de login
	      return \Redirect::to('/home');

	    $following = $logged_user->following;

	    
	    if ($user_id != $logged_user->id && $following->contains($user_id)) {
	      $logged_user->following()->detach($user_id);

	      $eventContent['target_userId'] = $user_id;
	      EventLogger::printEventLogs(null, 'unfollow', $eventContent, 'Web');
	    }

	    return \Redirect::to(\URL::previous()); // redirecionar para friends
	}


}
