<?php
namespace App\Models\Institutions;

use App\Models\Institution\Institution as Institution;
use User;
use Role;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Employee extends Model {

	protected $fillable = ['user_id','institution_id'];

	protected $collection = 'employees';

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function institution()
	{
		return $this->belongsTo('Institution');
	}

	public function role()
	{
		return $this->belongsTo('Role');
	}


}
