<?php
namespace modules\moderation\models;

class ModerationType extends \Eloquent {
	protected $fillable = ['moderation_type'];

	public function moderators(){
		return $this->hasMany('Moderator');
	}
}