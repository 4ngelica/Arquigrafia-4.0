<?php 
namespace App\Http\Controllers\Gamification;

use App\Models\Gamification\Badge;

class BadgesController extends Controller {

  public function show($id) {
    $badge = Badge::find($id);
    if ( is_null($badge) ) {
      return \Redirect::to('/home');
    }
    return \View::make('show_badge')->with(compact('badge'));
  }
}