<?php
namespace modules\collaborative\models;
use User;

class Follow extends \Eloquent {
	protected $table = "friendship";
	protected $fillable = ["followed_id", "following_id"];

	public function user() {
		return $this->belongsTo('User');
	}
}