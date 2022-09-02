<?php

namespace App\Http\Events\Subscriber;

use App\Models\Users\User;
use Notification;
use App\Models\Collaborative\Like;
use App\Models\Gamification\Badge;

class LikeSubscriber {

	public function onPhotoLiked($user, $photo) {
		if ( ($badge = Badge::getDestaqueDaSemana($photo)) ) {
    		Notification::create('badge_earned', $user, $badge, [$photo->user], null);
    	}
    }

	public function onPhotoDisliked($user, $photo){
	    try {
	      $like = Like::fromUser($user)->withLikable($photo)->first();
	      $like->delete();
	    } catch (Exception $e) {
	    }
	}

	public function onCommentDisliked($user, $comment) {
	    try {
	      $like = Like::fromUser($user)->withLikable($comment)->first();
	      $like->delete();
	    } catch (Exception $e) {
	    }
	}

	public function subscribe($events){
		$events->listen('photo.like'     , 'App\Http\Events\Subscriber\LikeSubscriber@onPhotoLiked');
		$events->listen('photo.dislike'  , 'App\Http\Events\Subscriber\LikeSubscriber@onPhotoDisliked');
		$events->listen('comment.dislike', 'App\Http\Events\Subscriber\LikeSubscriber@onCommentDisliked');
	}
}
