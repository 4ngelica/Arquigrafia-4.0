<?php
namespace App\Models\Chat;

use Users;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Participant;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Block extends Model {
	protected fillable = ['user_id', 'blocked_id'];

	public function user(){
		$this->belongsTo('User');
	}
	public function block(){
		$this->belongsTo('User', 'blocked_id', 'user_id')
	}
}
