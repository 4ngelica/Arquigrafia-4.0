<?php
namespace modules\collaborative\controllers;
use User;
use Photo;

class GroupsController extends \BaseController {

	public function index()
	{
		$users = User::all();
		return \View::make('group-index',['users' => $users]);
	}

	public function show($id)
	{
		$user = User::whereid($id)->first();
		$group = Photo::whereid($id)->first();
		return \View::make('group-show',['users' => $user,'group' => $group]);
	}
}
