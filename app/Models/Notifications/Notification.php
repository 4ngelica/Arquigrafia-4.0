<?php
namespace App\Models\Notifications;
use User;
use Photo;

class Notification extends \Eloquent{

	protected $fillable = ['type', 'sender_id', 'sender_type', 'object_id', 'objetc_type', 'data', 'created_at', 'updated_at'];

	public function user(){
		return $this->belongsToMany('User', 'notification_user');
	}

	public static function isNotificationByUser($user_id, $note_sender_id, $note_data){
		if ($user_id == $note_sender_id) return true;
		elseif ($note_data != null) {
			$users = explode(":", $note_data);
			foreach ($users as $user) {
				if ($user == $user_id) return true;
			}
		}
		return false;
	}

}