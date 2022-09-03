<?php

namespace App\Http\Controllers\Collaborative;

use App\Models\Photos\Photo;
use App\Models\Users\User;
use App\Http\Controllers\Controller;

class GroupsController extends Controller {

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
