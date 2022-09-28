<?php
namespace App\Models\Evaluations;

use App\Models\Evaluations\Evaluation;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Binomial extends Model {

	protected $softDelete = true;
	protected $fillable = ['firstOption','secondOption'];

	public $timestamps = false;

	public function evaluations()
	{
		return $this->hasMany('App\Models\Evaluations\Evaluation');
	}

}
