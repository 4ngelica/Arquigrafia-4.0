<?php
namespace App\modules\evaluations\models;

use modules\evaluations\models\Evaluation;

class Binomial extends \Eloquent {

	protected $softDelete = true;
	protected $fillable = ['firstOption','secondOption'];

	public $timestamps = false;

	public function evaluations()
	{
		return $this->hasMany('Evaluation');
	}

}
