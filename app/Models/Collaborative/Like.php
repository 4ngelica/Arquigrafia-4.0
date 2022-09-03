<?php

namespace App\Models\Collaborative;

use App\Models\Users\User;
use App\Traits\Gamification\LikableGamificationTrait;
use Illuminate\Database\Eloquent\Model;

class Like extends Model {

	use LikableGamificationTrait;
	protected $table = "likes";
	protected $fillable = [ 'user_id', 'likable_id', 'likable_type' ];

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function likable()
	{
		return $this->morphTo();
	}

	public static function getFirstOrCreate($likable, $user) {
		return self::firstOrCreate([
				'user_id' => $user->id,
				'likable_id' => $likable->id,
				'likable_type' => get_class($likable)
			]);
	}

	public function scopeFromUser($query, $user) {
		$a = $query->where('user_id', $user->id);
		return $a;
	}

	public function scopeWithLikable($query, $likable) {
		return $query->where('likable_type', get_class($likable))
			->where('likable_id', $likable->id);
	}
}
