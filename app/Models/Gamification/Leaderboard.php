<?php
namespace App\Models\Gamification;
use User;
use Illuminate\Support\Collection as Collection;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Leaderboard extends Model {

  protected $fillable = ['type', 'count', 'user_id'];

  public function user() {
    return $this->belongsTo('User');
  }

  public function scopeFromUser($query, $user) {
    $id = $user instanceof User ? $user->id : $user;
    return $query->where('user_id', $id);
  }

  public function scopeFromUsers($query, $users) {
    $ids = $users;
    if ($users instanceof Collection) {
      $ids = array_unique($users->lists('id'));
    }
    return $query->whereIn('user_id', $ids);
  }

  public function increaseScore($save = true) {
    if($this->type == 'completion')
      $this->count += 5;
    else
      $this->count += 1;
    if ($save) {
      $this->save();
    }
  }

  public function decreaseScore($save = true) {
    $this->count -= 1;
    if ($save) {
      $this->save();
    }
  }

  public static function createFromUser($user) {
    static::create([
        'type' => 'uploads',
        'count' => 0,
        'user_id' => $user->id
      ]);
    static::create([
        'type' => 'evaluations',
        'count' => 0,
        'user_id' => $user->id
      ]);
    static::create([
        'type' => 'completion',
        'count' => 0,
        'user_id' => $user->id
      ]);
  }

  public static function increaseUserScore($user, $type) {
    $user_leaderboard = static::fromUser($user)->whereType($type)->first();
    $user_leaderboard->increaseScore();
  }

  public static function decreaseUserScore($user, $type) {
    $user_leaderboard = static::fromUser($user)->whereType($type)->first();
    $user_leaderboard->decreaseScore();
  }

  public static function decreaseUsersScores($users, $type) {
    $leaderboards = static::fromUsers($users)->whereType($type)->get();
    foreach ($leaderboards as $l) {
      $l->decreaseScore();
    }
  }

}
