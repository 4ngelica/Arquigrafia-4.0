<?php

namespace App\Models\Collaborative;

use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Models\Users\User;

class Follow extends Model {
	protected $collection = "friendship";
	protected $fillable = ["followed_id", "following_id"];

	public function user() {
		return $this->belongsTo('User');
	}
}
