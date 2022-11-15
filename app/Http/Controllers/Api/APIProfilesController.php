<?php

namespace App\Http\Controllers\Api;

use App\Models\Evaluations\Evaluation;
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Cache;



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
		$result = Cache::remember('getFollowers_'. $id, 60 * 5, function() use ($id, $request) {
			$user = User::find($id);
			$followers = $user->followers->map->only('name', '_id', 'photo');

			if(array_key_exists('limit', $request->query->all())) {
				$limit = $request->query->all()['limit'];
				$followers = $followers->take($limit);
			}
			return \Response::json($followers);
		});
		// dd($result);
		return $result;
	}

	public function getFollowing(Request $request, $id) {
		$user = User::find($id);
		$users = $user->following->map->only('name', '_id', 'photo');
		$institutions =  $user->followingInstitution;

		if(array_key_exists('limit', $request->query->all())) {
			$limit = $request->query->all()['limit'];
			$users = $users->take($limit);
			$institutions = $institutions->take($limit);
		}

		return \Response::json(["users" => $users, "institutions" => $institutions]);
	}
}
