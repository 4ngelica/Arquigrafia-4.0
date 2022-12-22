<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\Gamification\Gamified;
use App\Models\Moderation\Suggestion;
use Illuminate\Http\Request;
use App\Models\Photos\Photo;
use Auth;

class ContributionsController extends Controller {

  /**
   * Controller function for /users/contributions page
   * @return  {View}  Present 'show-contributions' page
   */
  public function showContributions() {

    $auth = \Auth::user();

    if(!$auth) {
      return redirect('home');
    }

    $attributes = Photo::$attribute_types;

    $id = $auth->id;


    $editions = Suggestion::raw((function($collection) use ($id) {
        return $collection->aggregate([
          [
           '$lookup' => [
              'from' => 'photos',
              'localField' => 'photo_id',
              'foreignField'=> 'id',
              'as' => 'photo'
            ]
         ],
         [
           '$match' => [
             'photo.id' => [
               '$exists'=> true
             ],
           ]
         ],
         [
          '$lookup' => [
             'from' => 'users',
             'localField' => 'user_id',
             'foreignField'=> 'id',
             'as' => 'user'
           ]
        ],
       ]);
    }))->where('user_id', $id)->where('type', 'edition');


    $reviews = Suggestion::raw((function($collection) use ($id, $attributes) {
        return $collection->aggregate([
          [
           '$lookup' => [
              'from' => 'photos',
              'localField' => 'photo_id',
              'foreignField'=> 'id',
              'as' => 'photo'
            ]
         ],
         [
           '$match' => [
             'photo.id' => [
               '$exists'=> true
             ],
           ]
         ],
         [
          '$lookup' => [
             'from' => 'users',
             'localField' => 'photo.user_id',
             'foreignField'=> 'id',
             'as' => 'user'
           ]
        ],
       ]);
    }))->where('user_id', $id)->where('type', 'review');

    $refused_editions = Suggestion::raw((function($collection) use ($id) {
        return $collection->aggregate([
          [
           '$lookup' => [
              'from' => 'photos',
              'localField' => 'photo_id',
              'foreignField'=> 'id',
              'as' => 'photo'
            ]
         ],
         [
           '$match' => [
             'accepted' => false,
             'photo.id' => [
               '$exists'=> true
              ],
           ]
         ],
         [
          '$lookup' => [
             'from' => 'users',
             'localField' => 'photo.user_id',
             'foreignField'=> 'id',
             'as' => 'user'
           ]
        ]
       ]);
    }))->where('user_id', $id)->where('type', 'edition');

    $refused_reviews = Suggestion::raw((function($collection) use ($id) {
        return $collection->aggregate([
          [
           '$lookup' => [
              'from' => 'photos',
              'localField' => 'photo_id',
              'foreignField'=> 'id',
              'as' => 'photo'
            ]
         ],
         [
           '$match' => [
             'accepted' => false,
             'photo.id' => [
               '$exists'=> true
              ],
           ]
         ],
         [
          '$lookup' => [
             'from' => 'users',
             'localField' => 'photo.user_id',
             'foreignField'=> 'id',
             'as' => 'user'
           ]
        ]
       ]);
    }))->where('user_id', $id)->where('type', 'review');


    // foreach ($editions as $key => $value) {
    //   $value->field = Photo::$attribute_types[$value->attribute_type];
    // }
    //
    // foreach ($reviews as $key => $value) {
    //   $value->field = Photo::$attribute_types[$value->attribute_type];
    // }


    $accepted_editions = $editions->where('accepted', true);
    $waiting_editions = $editions->where('accepted', false);

    $accepted_reviews = $reviews->where('accepted', true);
    $waiting_reviews = $reviews->where('accepted', false);

    // dd($editions, $reviews, $accepted_reviews, $refused_reviews, $waiting_reviews, $accepted_editions, $refused_editions, $waiting_editions);

    // dd($refused_editions);

    return view('moderation.show-contributions', compact('auth', 'editions', 'reviews', 'accepted_editions', 'refused_editions', 'waiting_editions', 'accepted_reviews', 'refused_reviews', 'waiting_reviews'));
  }

}
