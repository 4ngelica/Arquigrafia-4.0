<?php
use modules\notifications\models\Notification as Notification;
use modules\collaborative\models\Like as Like;
use modules\collaborative\models\Comment as Comment;
use Carbon\Carbon;

Photo::deleted(function($photo){
    $all_users = User::all();
    foreach ($all_users as $users) {
      foreach ($users->notifications as $notes) {
        $curr_note = DB::table('notifications')->where('id', $notes->notification_id)->first();
        if (!is_null($curr_note)) {
          if ($curr_note->type == 'photo_liked') {
            if ($curr_note->object_id == $photo->id) {
              DB::table('notifications')->where('id', $notes->notification_id)->delete();
              $notes->delete();
            }
          }
          if ($curr_note->type = 'comment_liked' || $curr_note->type = 'comment_posted') {
            $note_photo = null;
            $note_comment = Comment::find($curr_note->object_id);
            if (!is_null($note_comment)) {
              if ($photo->id == $note_comment->photo_id) {
                DB::table('notifications')->where('id', $notes->notification_id)->delete();
                $notes->delete();
              }
            }
          }
        }
      }
    }
});

Like::created(function($like){
	$user = User::find($like->user_id);
	if($like->likable_type == 'Photo'){
		$photo = Photo::find($like->likable_id);
		if ($user->id != $photo->user_id) {
            $user_note = User::find($photo->user_id);
			\Notification::create('photo_liked', $user, $photo, [$user_note], null);
		}
	}
	else{
        $comment = Comment::find($like->likable_id);
		if ($user->id != $comment->user_id) {
	        $user_note = User::find($comment->user_id);
	        \Notification::create('comment_liked', $user, $comment, [$user_note], null);
	    }
	}
});


Comment::created(function($comment){
	$user = User::find($comment->user_id);
	$photo = Photo::find($comment->photo_id);
	if ($user->id != $photo->user_id) { 
        $user_note = User::find($photo->user_id);
        foreach ($user_note->notifications as $notification) {
            $info = $notification->render();
            if ($info[0] == "comment_posted" && $info[2] == $photo->id && 
            									$notification->read_at == null) {
                $note_id = $notification->notification_id;
                $note_user_id = $notification->id;
                $note = $notification;
            }
        }
        if (isset($note_id)) {
            $note_from_table = \DB::table("notifications")->where("id","=", $note_id)->get();
            if (Notification::isNotificationByUser($user->id, 
            												   $note_from_table[0]->sender_id, 
            												   $note_from_table[0]->data) == false) {
                $new_data = $note_from_table[0]->data . ":" . $user->id;
                \DB::table("notifications")->where("id", "=", $note_id)
                						   ->update(array("data" => $new_data, 
                						   				  "created_at" => Carbon::now('America/Sao_Paulo')));
                $note->created_at = Carbon::now('America/Sao_Paulo');
                $note->save();  
            }
        }
        else \Notification::create('comment_posted', $user, $comment, [$user_note], null);
    }
});