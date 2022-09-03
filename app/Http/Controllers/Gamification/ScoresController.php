<?php

namespace App\Http\Controllers\Gamification;

//use App\Models\Gamification\Score;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users\User;

class ScoresController extends Controller {

  public function getLeaderboard(Request $request) {
    $score_type = $request->get('type', 'uploads');
    $user_page = 0;
    if ( ! in_array($score_type, ['uploads', 'evals']) ) {
      $score_type = 'uploads';
    }
    if ( \Auth::check() ) {
      $user_page = \Auth::user()->getLeaderboardLocation($score_type);
    }
    $users = User::withScoreType($score_type, '*')->paginate(10);
    $count = ($users->currentPage() - 1) * 10 + 1;
    if ( \Request::ajax() ) {
      return $this->getNextPage($score_type, $users, $count, $user_page);
    }
    $current_page = $users->currentPage();
    return view('gamification.leaderboard')
      ->with(
        compact('users', 'count', 'score_type',
          'user_page', 'current_page')
      );
  }

  public function getNextPage($score_type, $users, $count, $user_page) {
    $view = view('gamification.leaderboard_users')
      ->with(compact('score_type', 'users', 'count'))
      ->render();
    $current_page = $users->currentPage();
    return \Response::json(
      compact('score_type', 'current_page', 'view', 'user_page')
    );
  }

}
