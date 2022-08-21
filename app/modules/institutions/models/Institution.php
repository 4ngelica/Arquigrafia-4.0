<?php
namespace App\modules\institutions\models;

use App\Models\Photos\Photo;
use App\Models\Users\User;
use Illuminate\Support\Collection as Collection;
use Session;
use App\modules\institutions\models\Employee;


class Institution extends \Eloquent {

	protected $fillable = ['name','country'];
	protected $softDelete = true;

	public function employees()
	{
		return $this->hasMany('App\modules\collaborative\models\Employee');
	}

	public function photos()
	{
		return $this->hasMany('App\Models\Photos\Photo');
	}

	public function albums()
	{
		return $this->hasMany('App\Models\Albums\Album');
	}

	public static function institutionsList()
	{
		return \DB::table('institutions')->orderBy('name', 'asc')->lists('name','id');
	}

	public static function belongInstitution($photo_id,$institution_id) {
		$belong = false;
		$photoInstitution = \DB::table('photos')->where('id',$photo_id)
		->where('institution_id',$institution_id)->get();

		if(!is_null($photoInstitution) && !empty($photoInstitution)){
			$belong = true;
		}

		return $belong;
	}

	public static function belongSomeInstitution($photo_id) {
		$exist = false;
	  	$valueInstitution = \DB::table('photos')
      	  ->select('institution_id')
      	  ->where('id',$photo_id)
      	  ->first();
     	if(!is_null($valueInstitution) && $valueInstitution->institution_id != null){
			$exist = true;
		}
		return $exist;
	}

	public function equal($institution) {
		try {
			return $institution instanceof Institution &&
				$this->id == $institution->id;
		} catch (Exception $e) {
			return false;
		}
	}

	//followers to institutions
	public function followersInstitutions()
	{
		return $this->belongsToMany('User', 'friendship_institution', 'institution_id','following_user_id');
	}

 	public static function RoleOfInstitutionalUser($userId)
	{
		$roles = \Role::where('name', 'LIKE', '%Respon%')->first();
        $query = Employee::where('user_id', '=', $userId)
                            ->where('institution_id', '=', Session::get('institutionId'))
                            ->where('role_id', '=',$roles->id)
                            ->first();
        return $query;
	}



	public static function paginatePhotosInstitution($id,$institution,$perPage = 30){
			return $institution->photos()->orderBy('photos.created_at', 'DESC')->paginate($perPage);
	}




}
