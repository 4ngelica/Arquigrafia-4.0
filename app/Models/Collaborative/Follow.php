<?php

namespace App\Models\Collaborative;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class Follow extends Model {
	protected $table = "friendship";
	protected $fillable = ["followed_id", "following_id"];

	public function user() {
		return $this->belongsTo('User');
	}
}
