<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\Gamification\Gamified;
use App\Models\Moderation\Suggestion;
use Illuminate\Http\Request;
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

    $id = $auth->id;

    // $reviews = Suggestion::where('user_id', '8')->where('type', 'review')->get();
    // $editions = Suggestion::where('user_id', '8')->where('type', 'edition')->get();

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
        // [
        //   '$match' => [
        //     'user.id' => [
        //       '$exists'=> true
        //     ],
        //   ]
        // ],
        [
         '$lookup' => [
            'from' => 'photo_attribute_types',
            'localField' => 'attribute_type',
            'foreignField'=> 'id',
            'as' => 'attribute_type'
          ]
       ],
       ]);
    }))->where('user_id', '8')->where('type', 'edition');

    $reviews = Suggestion::raw((function($collection) use ($id) {
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
        // [
        //   '$match' => [
        //     'user.id' => [
        //       '$exists'=> true
        //     ],
        //   ]
        // ],
        [
         '$lookup' => [
            'from' => 'photo_attribute_types',
            'localField' => 'attribute_type',
            'foreignField'=> 'id',
            'as' => 'attribute_type'
          ]
       ],
       ]);
    }))->where('user_id', '8')->where('type', 'review');

    $waiting_reviews = Suggestion::raw((function($collection) use ($id) {
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
             'accepted' => null,
             'photo.id' => [
               '$exists'=> true
              ],
           ]
         ],
         // [
         //   '$match' => [
         //     'photo.id' => [
         //       '$exists'=> true
         //     ],
         //   ]
         // ],
         [
          '$lookup' => [
             'from' => 'users',
             'localField' => 'photo.user_id',
             'foreignField'=> 'id',
             'as' => 'user'
           ]
        ],
        // [
        //   '$match' => [
        //     'user.id' => [
        //       '$exists'=> true
        //     ],
        //   ]
        // ],
        [
         '$lookup' => [
            'from' => 'photo_attribute_types',
            'localField' => 'attribute_type',
            'foreignField'=> 'id',
            'as' => 'attribute_type'
          ]
       ],
       ]);
    }))->where('user_id', '8')->where('type', 'review');

    $waiting_editions = Suggestion::raw((function($collection) use ($id) {
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
             'accepted' => null,
             'photo.id' => [
               '$exists'=> true
              ],
           ]
         ],
         // [
         //   '$match' => [
         //     'photo.id' => [
         //       '$exists'=> true
         //     ],
         //   ]
         // ],
         [
          '$lookup' => [
             'from' => 'users',
             'localField' => 'photo.user_id',
             'foreignField'=> 'id',
             'as' => 'user'
           ]
        ],
        // [
        //   '$match' => [
        //     'user.id' => [
        //       '$exists'=> true
        //     ],
        //   ]
        // ],
        [
         '$lookup' => [
            'from' => 'photo_attribute_types',
            'localField' => 'attribute_type',
            'foreignField'=> 'id',
            'as' => 'attribute_type'
          ]
       ],
       ]);
    }))->where('user_id', '8')->where('type', 'edition');

    $accepted_editions = $editions->where('accepted', 1);
    $refused_editions = $editions->where('accepted', 0);
    // $waiting_editions = $editions->where('accepted', '!=', 1)->where('accepted', '!=', 0);

    $accepted_reviews = $reviews->where('accepted', 1);
    $refused_reviews = $reviews->where('accepted', 0);
    // $waiting_reviews = $reviews->where('accepted', '!=', 1)->where('accepted', '!=', 0);

    // dd($editions, $reviews, $accepted_reviews, $refused_reviews, $waiting_reviews, $accepted_editions, $refused_editions, $waiting_editions);

    return view('moderation.show-contributions', compact('auth', 'editions', 'reviews', 'accepted_editions', 'refused_editions', 'waiting_editions', 'accepted_reviews', 'refused_reviews', 'waiting_reviews'));
  }

}
