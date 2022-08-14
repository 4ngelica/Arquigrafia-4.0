<?php 
namespace modules\api\controllers;
use modules\evaluations\models\Evaluation;

class APIProfilesController extends \BaseController {

	public function getProfile($id) {
		$user = \User::find($id);
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

	public function getFollowers($id) {
		$user = \User::find($id);
		return \Response::json($user->followers);
	}

	public function getFollowing($id) {
		$user = \User::find($id);
		return \Response::json(["users" => $user->following, "institutions" => $user->followingInstitution]);
	}
}