<?php
namespace modules\chat\models;

use Users;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Participant;

class Block extends \Eloquent {
	protected fillable = ['user_id', 'blocked_id'];

	public function user(){
		$this->belongsTo('User');
	}
	public function block(){
		$this->belongsTo('User', 'blocked_id', 'user_id')
	}
}