<?php

namespace App\Models\Moderation;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Suggestion extends Model {

	protected $fillable = ['user_id', 'photo_id', 'attribute_type', 'text' , 'accepted', 'moderator_id'];

	public function photo_attribute_type() {
		return $this->belongsTo('App\Models\Moderation\PhotoAttributeType', 'attribute_type');
	}

	public function user(){
		return $this->belongsTo('App\Models\Users\User');
	}

	public function photo(){
		return $this->belongsTo('App\Models\Photos\Photo');
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
