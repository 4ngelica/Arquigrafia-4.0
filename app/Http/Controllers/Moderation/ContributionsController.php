<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\modules\gamification\models\Gamified;
use Auth;

class ContributionsController extends Controller {
  public function __construct() {
    // Filtering if user is logged out, redirect to login page
    // $this->beforeFilter(
    //   'auth',
    //   array('except' => [])
    // );
  }

  /**
   * Controller function for /users/contributions page
   * @return  {View}  Present 'show-contributions' page
   */
  public function showContributions(Request $request) {
    // This page has a gamified variant, get the gamified variant
    $variationId = Gamified::getGamifiedVariationId();
    $isGamified = Gamified::isGamified($variationId);
    // Getting data
    $user = \Auth::user();
    $input = $request->all();
    // The page can already load with a pre-selected tab
    $tab = null;
    // Checking if page received a 'tab' input
    if (isset($input['tab'])) {
      $tab = $input['tab'];
    }
    // The page can already load with a pre-selected filter
    $filter = null;
    // Checking if page received a 'filter' input
    if (isset($input['filter'])) {
      $filter = $input['filter'];
    }

    return view('show-contributions', [
      'currentUser' => $user,
      'isGamefied' => $isGamified,
      'variationId' => $variationId,
      'selectedTab' => $tab,
      'selectedFilterId' => $filter
    ]);
  }

}
