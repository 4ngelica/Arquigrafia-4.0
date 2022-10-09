<?php

namespace App\Models\Moderation;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class PhotoAttributeType extends Model {
	protected $fillable = ['attribute'];

	public function suggestions(){
		return $this->hasMany('App\Models\Moderation\Suggestion', 'attribute_type');
	}
}
