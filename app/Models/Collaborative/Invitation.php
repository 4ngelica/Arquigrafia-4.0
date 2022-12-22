<?php

namespace App\Models\Collaborative;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Invitation extends Model
{
	protected $table = 'invitations';
	protected $fillable = array('code','email','expiration','active','used');

}
