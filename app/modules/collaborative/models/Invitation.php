<?php 
namespace modules\collaborative\models;
use Illuminate\Database\Eloquent\Model;

class Invitation extends \Eloquent
{
	protected $table = 'invitations';
	protected $fillable = array('code','email','expiration','active','used');

}
