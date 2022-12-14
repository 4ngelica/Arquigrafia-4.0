<?php

namespace App\Http\Controllers\Api;

use App\Models\Evaluations\Evaluation;
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use App\Models\Photos\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Institutions\Institution;
use App\Models\Collaborative\Follow;
use App\Models\Collaborative\FollowInstitution;


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

		$evaluations = Evaluation::raw((function($collection) use ($id) {
				return $collection->aggregate([
					[
					 '$lookup' => [
							'from' => 'photos',
							'localField' => 'photo_id',
							'foreignField'=> 'id',
							'as' => 'photo'
						],
				 ],
				 [
					 '$match' => [
						 'photo.user_id' => $id,
						 'photo.id' => [
							 '$exists'=> true
						 ],
					 ]
				 ],
				[
					'$project' => [
						'photo_id' => 1,
						'photo.id' => 1,
						'photo.name' => 1,
					]
				],
				]);
		}));

		$evaluations = $evaluations->unique('photo_id');

		// $evaluations = Evaluation::where('user_id', $id)->get()->unique('photo_id');

		return \Response::json(['evaluations'=>array_values($evaluations->toArray())]);
		// return \Response::json(['evaluations'=> $evaluations]);
	}

	public function getMoreUserEvaluations($id) {
		$input = \Input::all();
		$max_id = $input["max_id"];

		$evaluations = Evaluation::where("user_id", $id)->where("id", "<", $max_id)->groupBy('photo_id')->distinct()->orderBy('id', 'desc')->take(20)->get();
		return \Response::json(["photos" => \Photo::whereIn('id', $evaluations->lists('photo_id'))->get(), "max_id" => $evaluations[count($evaluations)-1]->id]);
	}

	public function getFollowers(Request $request, $id) {

		$users = Follow::raw((function($collection) use ($id) {
				return $collection->aggregate([
					[
					 '$lookup' => [
							'from' => 'users',
							'localField' => 'following_id',
							'foreignField'=> 'id',
							'as' => 'user'
						],
				 ],
				 [
					 '$match' => [
						 'followed_id' => $id,
						 'user.id' => [
							 '$exists'=> true
						 ],
					 ]
				 ],
						[
							'$project' => [
								'user.id' => 1,
								'user.name' => 1,
								'user.photo' => 1,
							]
					 ]
				]);
		}));

		if(array_key_exists('limit', $request->query->all())) {
			$limit = $request->query->all()['limit'];
			$users = $users->take($limit);
		}

		return \Response::json(['users' => $users]);
	}

	public function getFollowing(Request $request, $id) {
		$users = Follow::raw((function($collection) use ($id) {
				return $collection->aggregate([
					[
					 '$lookup' => [
							'from' => 'users',
							'localField' => 'followed_id',
							'foreignField'=> 'id',
							'as' => 'user'
						],
				 ],
				 [
					 '$match' => [
						 'following_id' => $id,
						 'user.id' => [
							 '$exists'=> true
						 ]
					 ]
				 ],
						[
							'$project' => [
								'user.id' => 1,
								'user.name' => 1,
								'user.photo' => 1,
							]
					 ]
				]);
		}));

		 $institutions = FollowInstitution::raw((function($collection) use ($id) {
				 return $collection->aggregate([
					 [
						'$lookup' => [
							 'from' => 'institutions',
							 'localField' => 'following_user_id',
							 'foreignField'=> 'id',
							 'as' => 'institution'
						 ],
					],
					[
						'$match' => [
							'following_user_id' => $id,
							'institution.id' => [
								'$exists'=> true
							]
						]
					],
						 [
							 '$project' => [
						     'institution.name' => 1
						   ]
						]
				 ]);
		}));

		if(array_key_exists('limit', $request->query->all())) {
			$limit = $request->query->all()['limit'];
			$users = $users->take($limit);
		}

		 return \Response::json(['users' => $users, 'institutions' => $institutions], 200);
	}

	public function follow(Request $request, $id) {

		$friendship = DB::collection('friendship')->where('following_id', $request->user_id)->where('followed_id', $id)->first();

		if($friendship) {
			return \Response::json(['msg' => 'Usuário já é seguido'], 400);
		}

		$friendship = DB::collection('friendship')->insert(['following_id' => $request->user_id, 'followed_id' => $id]);

		return \Response::json(['msg' => 'Usuário seguido'], 200);
	}

	public function unfollow(Request $request, $id) {

		$friendship = DB::collection('friendship')->where('following_id', $request->user_id)->where('followed_id', $id)->get();

		if(empty($friendship)) {
			return \Response::json(['msg' => 'Usuário já não é seguido'], 400);
		}

		$friendship = Follow::where('following_id', $request->user_id)->where('followed_id', $id)->first();

		$friendship->delete();


		return \Response::json(['msg' => 'Usuário deixou de ser seguido'], 200);
	}
}
