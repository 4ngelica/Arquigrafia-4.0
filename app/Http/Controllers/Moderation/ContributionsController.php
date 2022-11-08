<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\Gamification\Gamified;
use Illuminate\Http\Request;
use Auth;

class ContributionsController extends Controller {

  /**
   * Controller function for /users/contributions page
   * @return  {View}  Present 'show-contributions' page
   */
  public function showContributions() {

    $user = \Auth::user();

    return view('moderation.show-contributions', [
      'currentUser' => $user,
    ]);
  }

}
