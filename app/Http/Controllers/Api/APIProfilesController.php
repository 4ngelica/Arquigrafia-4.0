<?php

namespace App\Http\Controllers\Api;

use App\Models\Evaluations\Evaluation;
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Institutions\Institution;



class APIProfilesController extends Controller {

	public function getProfile($id) {
		$user = User::find($id);
		return \Response::json(array_merge($user->toArray(), ["followers" => count($user->followers), "following" => (count($user->following) + count($user->followingInstitution)), "photos" => count($user->photos)]));
	}

	public function getUserPhotos($id) {
		$user_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('institution_id')->where('user_id', '=', $id)->orderBy('created_at', 'desc')->take(20)->get();
		return \Response::json($user_photos);
	}

	public function getMoreUserPhotos($id) {
		$input = \Input::all();
		$max_id = $input["max_id"];

		$user_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('institution_id')->where('user_id', '=', $id)->where('id', '<', $max_id)->orderBy('created_at', 'desc')->take(20)->get();
		return \Response::json($user_photos);
	}

	public function getUserEvaluations($id) {
		$evaluations = Evaluation::where("user_id", $id)->groupBy('photo_id')->distinct()->orderBy('id', 'desc')->take(20)->get();
		return \Response::json(["photos" => \Photo::whereIn('id', $evaluations->lists('photo_id'))->get(), "max_id" => $evaluations[count($evaluations)-1]->id]);
	}

	public function getMoreUserEvaluations($id) {
		$input = \Input::all();
		$max_id = $input["max_id"];

		$evaluations = Evaluation::where("user_id", $id)->where("id", "<", $max_id)->groupBy('photo_id')->distinct()->orderBy('id', 'desc')->take(20)->get();
		return \Response::json(["photos" => \Photo::whereIn('id', $evaluations->lists('photo_id'))->get(), "max_id" => $evaluations[count($evaluations)-1]->id]);
	}

	public function getFollowers(Request $request, $id) {

		$users = [];

		// dd(DB::collection('friendship')->get());

		// dd(DB::collection('friendship')->where('followed_id', $id)->get());

		if(array_key_exists('limit', $request->query->all())) {
			$limit = $request->query->all()['limit'];
			$followers = DB::collection('friendship')->where('followed_id', $id)->take($limit)->get(['following_id'])->toArray();
		}else {
			$followers = DB::collection('friendship')->where('followed_id', $id)->get(['following_id'])->toArray();
		}


		foreach($followers as &$follower){
			$user = User::where('_id', $follower['following_id'])->get(['name', 'photo']);

			if($user->isNotEmpty()) {
				array_push($users, $user->first());
			}
		}

		return \Response::json(['users' => $users]);
	}

	public function getFollowing(Request $request, $id) {

		$users = [];
		$institutions = [];
		// dd(DB::collection('friendship')->get());

		// dd(DB::collection('friendship')->where('following_id', $id)->get());

		if(array_key_exists('limit', $request->query->all())) {
			$limit = $request->query->all()['limit'];
			$followeds = DB::collection('friendship')->where('following_id', $id)->take($limit)->get(['followed_id'])->toArray();
		}else {
			$followeds = DB::collection('friendship')->where('following_id', $id)->get(['followed_id'])->toArray();
		}

		foreach($followeds as &$followed){
			$user = User::where('_id', $followed['followed_id'])->get(['name', 'photo']);
			$institution = Institution::where('_id', $followed['followed_id'])->get(['name', 'photo']);

			if($user->isNotEmpty()) {
				array_push($users, $user->first());
			}

			if($institution->isNotEmpty()) {
				array_push($institutions, $institution->first());
			}

		}

		return \Response::json(['users' => $users, 'institutions' => $institutions]);
	}
}
