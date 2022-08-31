<?php

namespace App\Http;

use App\Models\Users\User;
use Notification;
use App\Models\Collaborative\Like;
use App\modules\gamification\models\Badge;

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
		$events->listen('photo.like'     , 'modules\collaborative\subscriber\LikeSubscriber@onPhotoLiked');
		$events->listen('photo.dislike'  , 'modules\collaborative\subscriber\LikeSubscriber@onPhotoDisliked');
		$events->listen('comment.dislike', 'modules\collaborative\subscriber\LikeSubscriber@onCommentDisliked');
	}
}
