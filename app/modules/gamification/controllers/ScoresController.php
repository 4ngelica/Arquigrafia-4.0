<?php 
namespace modules\gamification\controllers;
//use modules\gamification\models\Score;


class ScoresController extends \BaseController {

  public function getLeaderboard() {
    $score_type = \Input::get('type', 'uploads');
    $user_page = 0;
    if ( ! in_array($score_type, ['uploads', 'evals']) ) {
      $score_type = 'uploads';
    }
    if ( \Auth::check() ) {
      $user_page = \Auth::user()->getLeaderboardLocation($score_type);
    }
    $users = \User::withScoreType($score_type, '*')->paginate(10);
    $count = ($users->getCurrentPage() - 1) * 10 + 1;
    if ( \Request::ajax() ) {
      return $this->getNextPage($score_type, $users, $count, $user_page);
    }  
    return \View::make('leaderboard')
      ->with(
        compact('users', 'count', 'score_type',
          'user_page', 'current_page')
      );
  }

  public function getNextPage($score_type, $users, $count, $user_page) {
    $view = \View::make('leaderboard_users')
      ->with(compact('score_type', 'users', 'count'))
      ->render();
    $current_page = $users->getCurrentPage();
    return \Response::json(
      compact('score_type', 'current_page', 'view', 'user_page')
    );
  }

}