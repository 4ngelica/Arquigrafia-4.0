<?php

namespace App\Models\Collaborative;

use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Models\Users\User;

class FollowInstitution extends Model {
	protected $collection = "friendship_institution";
	protected $fillable = ["institution_id", "following_user_id"];

	// public function user() {
	// 	return $this->belongsTo('User');
	// }
}
