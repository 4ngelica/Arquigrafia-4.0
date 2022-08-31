<?php

namespace App\Models\Users;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Occupation extends Eloquent {

	public $timestamps = false;

	protected $fillable = ['id', 'institution', 'occupation', 'user_id'];

	public function user()
	{
		return $this->belongsTo('User');
	}

	public static function userOccupation($user_id){
		if($user_id != null){
			$arrayOccupations = array();
			$evaluations = DB::table('occupations')
			->select('occupation')
			->where('user_id', $user_id)->get();

			foreach ($evaluations as $valOccupations) {

				 $arrayOccupations[] = $valOccupations;
			}
			 return $arrayOccupations;
		}

	}
}
