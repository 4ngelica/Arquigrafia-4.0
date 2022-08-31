<?php

namespace App\Models\Moderation;

use Illuminate\Database\Eloquent\Model;

class PhotoAttributeType extends Model {
	protected $fillable = ['attribute'];

	public function suggestions(){
		return $this->hasMany('App\Models\Moderation\Suggestion', 'attribute_type');
	}
}
