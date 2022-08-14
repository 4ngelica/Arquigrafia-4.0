<?php
namespace modules\moderation\models;

class Moderator extends \Eloquent {
	protected $fillable = ['user_id', 'moderation_type_id', 'level'];

	public function moderation_type(){
		return $this->belongsTo('ModerationType');
	}

	public function user(){
		return $this->hasOne('User');
	}
}