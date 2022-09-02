<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class AudiosController extends Controller {

	public function store() {

		$name = time() + '.mp3';

		$audio = new Audio;
		$audio->file = $name;
		$audio->user_id = Input::get('user');
		$audio->photo_id = Input::get('photo');
		if ($audio->save()) {
			return Response::json(['status'=>'success']);
		}
		return Response::json($audio->getErrors());
	}

}
