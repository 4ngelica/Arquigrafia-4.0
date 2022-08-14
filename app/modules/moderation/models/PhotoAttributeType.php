<?php
namespace modules\moderation\models;

class PhotoAttributeType extends \Eloquent {
	protected $fillable = ['attribute'];

	public function suggestions(){
		return $this->hasMany('Suggestion', 'attribute_type');
	}
}
