<?php

namespace App\Models\Moderation;

use Illuminate\Database\Eloquent\Model;

class ModerationType extends Model {

	protected $fillable = ['moderation_type'];

	public function moderators(){
		return $this->hasMany('App\Models\Moderation\Moderator');
	}
}
