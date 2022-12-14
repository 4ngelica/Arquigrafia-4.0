<?php
namespace App\Models\Evaluations;

use App\Models\Evaluations\Binomial;
use App\Models\Users\User;
use App\Models\Photos\Photo;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Evaluation extends Model {

	protected $connection = 'mongodb';
	protected $collection = 'binomial_evaluation';
	protected $primaryKey = 'id';


	// protected $softDelete = true;
	protected $fillable = ['id', 'photo_id','evaluationPosition','binomial_id','user_id','knownArchitecture', 'areArchitecture'];

	// protected $table = 'binomial_evaluation';

	public $timestamps = false;

	public function binomial()
	{
		return $this->hasOne('Binomial');
	}

	public function user()
	{
			return $this->belongsTo('User');
	}

	public function photo()
	{
		return $this->hasOne('Photo');
	}

	public static function average($id) {
		 return \DB::table('binomial_evaluation')
			->select('binomial_id', \DB::raw('avg(evaluationPosition) as avgPosition'))
			->where('photo_id', $id)
			->orderBy('binomial_id', 'asc')
			->groupBy('binomial_id')->get();
	}


	public static function userKnowsArchitecture($photoId,$userId){
		   $result = \DB::table('binomial_evaluation')
			->select('knownArchitecture')
			->where('photo_id', $photoId)
			->where('user_id',$userId)->first();
		   if($result != null && $result[0] != null && $result[0]->knownArchitecture == 'yes'){
		   		return true;
		   }else{
		   		return false;
		   }

	}

	public static function userAreArchitecture($photoId,$userId){
		$result = \DB::table('binomial_evaluation')
				->select('areArchitecture')
				->where('photo_id', $photoId)
				->where('user_id',$userId)->first();
		if($result != null && $result[0] != null && $result[0]->areArchitecture == 'yes'){
				return true;
		}else{
				return false;
		}
}

	public static function averageAndUserEvaluation($photoId,$userId) {
		$avgPhotosBinomials = \DB::table('binomial_evaluation')
		->select('binomial_id', \DB::raw('avg(evaluationPosition) as avgPosition'))
		->where('photo_id', $photoId)
		->orderBy('binomial_id', 'asc')
		->groupBy('binomial_id')->get();

		$evaluations = null;
		if ($userId != null) {
			$evaluations = \DB::table('binomial_evaluation')
			->select('id','photo_id','evaluationPosition','binomial_id','user_id')
			->where('user_id', $userId)
			->where("photo_id", $photoId)
			->orderBy("binomial_id", "asc")->get();

			foreach ($evaluations as $valuesEvaluation) {
				foreach ($avgPhotosBinomials as $avgBinomials) {
					if ($avgBinomials->binomial_id == $valuesEvaluation->binomial_id) {
						$valuesEvaluation->avg = $avgBinomials->avgPosition;
						break;
					}
				}
			}
		}
		return $evaluations;
	}

	public static function getPhotosByBinomial( $binomial, $option, $value ) {
		if ( $value == null ) {
			$value = $binomial->defaultValue;
			$operator = $option == 1 ? '<' : '>';
			$avg = true;
		} else {
			$operator = '=';
			$avg = false;
		}
		$list = static::getListOfPhotosByBinomial($binomial, $operator, $value, $avg);
		return Photo::findMany($list)->all();
	}

	public static function getListOfPhotosByBinomial($binomial, $operator, $value, $avg) {
		$query = static::select('photo_id')->distinct()
			->withBinomial($binomial);
		if ( $avg ) {
			$query = $query->groupBy('photo_id')
				->withAverage($operator, $value);
		} else {
			$query = $query->withValue($operator, $value);
		}
		return $query->get()->lists('photo_id');
	}

	public function scopeWithBinomial($query, $binomial) {
		$binomial_id = $binomial instanceof Binomial ? $binomial->id : $binomial;
		return $query->where('binomial_id', $binomial_id);
	}

	public function scopeWithValue($query, $operator, $value) {
		return $query->where('evaluationPosition', $operator, $value);
	}

	public function scopeWithAverage($query, $operator, $value) {
		$aggregate = \DB::raw('avg(evaluationPosition)');
		return $query->having($aggregate, $operator, $value);
	}

	public static function searchByBinomialValues($binomial_values) {
		$query = "select distinct photo_id from binomial_evaluation where";
		$values = [];
		$and = 0;
		foreach ($binomial_values as $binomial => $value) {
			$min = intval($value) - 5; $max = intval($value) + 5;
			$query .= static::getEvaluationInRangeQuery($and++);
			array_push($values, $binomial, $min, $max);
		}
		$list_of_photos = array_map(function ($object) {
				return $object->photo_id;
			}, \DB::select($query, $values));
		return Photo::findMany($list_of_photos)->toArray();

	}

	public static function getEvaluationInRangeQuery($and) {
		$query = $and ? " and" : "";
		$innerQuery = "select photo_id from binomial_evaluation";
		$innerQuery .= " where binomial_id = ?";
		$innerQuery .= " group by photo_id";
		$innerQuery .= " having avg(evaluationPosition) >= ?";
		$innerQuery .= " and avg(evaluationPosition) <= ?";
		return $query . " photo_id in ({$innerQuery})";
	}

}
