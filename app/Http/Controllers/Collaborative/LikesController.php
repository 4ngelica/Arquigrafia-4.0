<?php

namespace App\Http\Controllers\Collaborative;

use App\Models\Collaborative\Like;
use App\Models\Collaborative\Comment;
use App\Models\Users\User;
use App\lib\log\EventLogger;
use App\modules\news\models\News;
use App\modules\gamification\models\Badge;
use Notification;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Photos\Photo;
use Auth;
use Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Event;

class LikesController extends Controller {

  public function index(){
    return redirect()->to('/home');
  }
  public function photoLike($id) {

    $photo = Photo::find($id);
    $user = Auth::user();
    if (is_null($photo)) {
      return Response::json('fail');
    }

    Event::dispatch('photo.like', array($user, $photo));

    EventLogger::printEventLogs(null, 'like', ['target_type' => 'foto', 'target_id' => $id], 'Web');

    if ($user->id != $photo->user_id) {
      $user_note = User::find($photo->user_id);
    }
    $like = Like::getFirstOrCreate($photo, $user);

    return Response::json([
      'url' => URL::to('/dislike/' . $photo->id),
      'likes_count' => $photo->likes->count()
    ]);
  }

  public function photoDislike($id)
  {
    $photo = Photo::find($id);
    $user = Auth::user();
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
      return Response::json('fail');
    }
    return Response::json([
      'url' => URL::to('/like/' . $photo->id),
      'likes_count' => $photo->likes->count()]);
  }
}
