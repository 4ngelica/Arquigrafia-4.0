<?php
namespace App\modules\gamification\traits;

use App\modules\collaborative\models\Like as Like;
use App\modules\collaborative\models\Comment;
use App\modules\gamification\models\Score;



trait LikableGamificationTrait {
  public function likes()
  {
    return $this->morphMany('App\modules\collaborative\models\Like', 'likable');
  }

  public function hasUserLike($user) {
    if (\Auth::check()) {
        $like = \App\modules\collaborative\models\Like::fromUser($user)->withLikable($this)->first();

      if ( ! is_null($like) ) {
        return true;
      }
    }
    return false;
  }

  public function countLikesAfterDate($date) {
    $last_week = \Carbon\Carbon::today()->subWeek();
    return $this->likes()
      ->where('created_at', '>=', (string) $last_week)->count();
  }
}
