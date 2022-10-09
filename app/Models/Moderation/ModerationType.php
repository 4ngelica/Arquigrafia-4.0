<?php

namespace App\Models\Moderation;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class ModerationType extends Model {

	protected $fillable = ['moderation_type'];

	public function moderators(){
		return $this->hasMany('App\Models\Moderation\Moderator');
	}
}
