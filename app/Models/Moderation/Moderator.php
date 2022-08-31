<?php

namespace App\Models\Moderation;

use Illuminate\Database\Eloquent\Model;

class Moderator extends Model {
	protected $fillable = ['user_id', 'moderation_type_id', 'level'];

	public function moderation_type(){
		return $this->belongsTo('App\Models\Moderation\ModerationType');
	}

	public function user(){
		return $this->hasOne('App\Models\Users\User');
	}
}
