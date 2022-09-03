<?php

namespace App\Traits\Gamification;

use App\Models\Collaborative\Like;
use App\Models\Collaborative\Comment;
use App\Models\Gamification\Score;



trait LikableGamificationTrait {
  public function likes()
  {
    return $this->morphMany('App\Models\Collaborative\Like', 'likable');
  }

  public function hasUserLike($user) {
    if (\Auth::check()) {
        $like = Like::fromUser($user)->withLikable($this)->first();

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
