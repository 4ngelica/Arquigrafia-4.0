<?php 
namespace modules\api\controllers;
use modules\institutions\models\Institution;

class APIFeedController extends \BaseController {

	public function loadFeed($id)
	{
		$user = \User::find($id);
		$following_users = $user->following;
		$following_institutions = $user->followingInstitution;
		$users_ids = $following_users->lists('id');
		$institutions_ids = $following_institutions->lists('id');
		$users_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('institution_id')->whereIn('user_id', $users_ids);
		$institutions_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('draft')->whereIn('institution_id', $institutions_ids);
		$all_photos = $institutions_photos->union($users_photos)->orderBy('created_at', 'desc')->take(20)->get();
		$result = [];
		foreach ($all_photos as $photo) {
			if(is_null($photo->institution_id)) {
				array_push($result, ["photo" => $photo, "sender" => \User::find($photo->user_id)]);
			}
			else {
				array_push($result, ["photo" => $photo, "sender" => Institution::find($photo->institution_id)]);	
			}
		}
		if(empty($result)) {
			$institutions_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('draft')->whereNotNull('institution_id')->orderBy('created_at', 'desc')->take(20)->get();
			foreach ($institutions_photos as $photo) {
				array_push($result, ["photo" => $photo, "sender" => Institution::find($photo->institution_id)]);
			}
		}
		return \Response::json($result);
	}

	public function loadMoreFeed($id) {
		$input = \Input::all();
		$max_id = $input["max_id"];

		$user = \User::find($id);
		$following_users = $user->following;
		$following_institutions = $user->followingInstitution;
		$users_ids = $following_users->lists('id');
		$institutions_ids = $following_institutions->lists('id');
		$users_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('institution_id')->where('id', '<', $max_id)->whereIn('user_id', $users_ids);
		$institutions_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('draft')->where('id', '<', $max_id)->whereIn('institution_id', $institutions_ids);
		$all_photos = $institutions_photos->union($users_photos)->orderBy('created_at', 'desc')->take(20)->get();
		$result = [];
		foreach ($all_photos as $photo) {
			if(is_null($photo->institution_id)) {
				array_push($result, ["photo" => $photo, "sender" => \User::find($photo->user_id)]);
			}
			else {
				array_push($result, ["photo" => $photo, "sender" => Institution::find($photo->institution_id)]);	
			}
		}
		if(empty($result)) {
			$institutions_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('draft')->whereNotNull('institution_id')->where('id', '<', $max_id)->orderBy('created_at', 'desc')->take(20)->get();
			foreach ($institutions_photos as $photo) {
				array_push($result, ["photo" => $photo, "sender" => Institution::find($photo->institution_id)]);
			}
		}
		return \Response::json($result);
	}

	public function loadRecentPhotos() {
		return \Response::json(\Photo::all()->sortByDesc('created_at')->take(20));
	}

	public function loadMoreRecentPhotos() {
		$input = \Input::all();
		$max_id = $input["max_id"];
		return \Response::json(\Photo::where('id', '<', $max_id)->orderBy('created_at', 'desc')->take(20)->get());
	}
}
