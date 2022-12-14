<?php
namespace App\traits\Gamification;

use App\Models\Gamification\Score;
use App\Models\Gamification\Badge;


trait UserGamificationTrait {

  public function badges() {
    return $this->belongsToMany('App\Models\Gamification\Badge','user_badges')
      ->withTimestamps()
      ->withPivot('element_type', 'element_id');
  }

  public function ranks() {
    return $this->hasMany('App\Models\Gamification\Rank');
  }

  public function scopeWithScoreType($query, $score_type) {
    $method = 'with' . ucfirst($score_type);
    return $query->$method();
  }

  public function scopeWithUploads($query) {
    return $query->selectRaw('users.*, leaderboards.count as uploads')
      ->join('leaderboards', function ($join) {
        $join->on('users.id', '=', 'leaderboards.user_id')
          ->where('leaderboards.type', '=', 'uploads');
      })->orderBy('uploads', 'desc');
  }

 public function scopeWithEvals($query) {
    return $query->selectRaw('users.*, leaderboards.count as evals')
      ->join('leaderboards', function ($join) {
        $join->on('users.id', '=', 'leaderboards.user_id')
          ->where('leaderboards.type', '=', 'evaluations');
      })->orderBy('evals', 'desc');
  }

  public function getLeaderboardLocation($score_type) {
    $u = static::withScoreType($score_type)->get();
    $location = array_search($this->id, $u->pluck('id')->all());
    return $location === false ? 0 : intval($location / 10) + 1;
  }
}
