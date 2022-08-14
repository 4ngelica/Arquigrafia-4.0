<?php namespace modules\collaborative\controllers;
use modules\collaborative\models\Like;
use modules\collaborative\models\Comment;
use lib\log\EventLogger;
use modules\news\models\News as News;
use modules\gamification\models\Badge;
use Notification;
use Carbon\Carbon;

class LikesController extends \BaseController {

  public function index(){
    return \Redirect::to('/home');
  }
  public function photoLike($id) {

    $photo = \Photo::find($id);
    $user = \Auth::user();
    if (is_null($photo)) {
      return \Response::json('fail');
    }

    \Event::fire('photo.like', array($user, $photo));

    EventLogger::printEventLogs(null, 'like', ['target_type' => 'foto', 'target_id' => $id], 'Web');

    if ($user->id != $photo->user_id) {
      $user_note = \User::find($photo->user_id);      
    }
    $like = Like::getFirstOrCreate($photo, $user);
    
    return \Response::json([ 
      'url' => \URL::to('/dislike/' . $photo->id),
      'likes_count' => $photo->likes->count()
    ]);
  }

  public function photoDislike($id) 
  {
    $photo = \Photo::find($id);
    $user = \Auth::user();
    $eventContent['target_type'] = 'foto';

    EventLogger::printEventLogs(null, 'dislike', ['target_type' => 'foto', 'target_id' => $id], 'Web');

    /* */
    try {
      $like = Like::fromUser($user)->withLikable($photo)->first();
      $like->delete();
    } catch (Exception $e) {
      //
    }
    if (is_null($photo)) {
      return \Response::json('fail');
    }
    return \Response::json([ 
      'url' => \URL::to('/like/' . $photo->id),
      'likes_count' => $photo->likes->count()]);
  }
}