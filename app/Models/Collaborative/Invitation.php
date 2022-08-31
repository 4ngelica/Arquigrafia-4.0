<?php

namespace App\Models\Collaborative;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
	protected $table = 'invitations';
	protected $fillable = array('code','email','expiration','active','used');

}
