<?php
namespace App\modules\moderation\models;

class Suggestion extends \Eloquent {
	protected $fillable = ['user_id', 'photo_id', 'attribute_type', 'text' , 'accepted', 'moderator_id'];

	public function photo_attribute_type() {
		return $this->belongsTo('PhotoAttributeType', 'attribute_type');
	}

	public function user(){
		return $this->belongsTo('User');
	}

	public function photo(){
		return $this->belongsTo('Photo');
	}

	/**
	 * This function return the number of each suggestion value as points
	 * For now returns a static number, on the future suggestions can have different values,
	 * depending on the type
	 */
	public function numPoints() {
		return 5;
	}
}
