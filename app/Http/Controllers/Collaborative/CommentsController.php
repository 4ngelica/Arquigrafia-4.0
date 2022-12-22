<?php

namespace App\Http\Controllers\Collaborative;

use App\Models\Collaborative\Comment;
use App\Models\Collaborative\Like;
use App\Models\Gamification\Badge;
use App\lib\date\Date;
use App\lib\log\EventLogger;
use App\Models\News\News;
use Illuminate\Http\Request;
use Auth;
use Notification;
use Carbon\Carbon;
use App\Models\Photos\Photo;
use App\Models\Users\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;


class CommentsController extends Controller {

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$comment = Comment::all();
    return $comment;
	}


	public function comment(Request $request, $id)
  {
    	$input = $request->all();
    	$rules = ['text' => 'required'];
    	$validator = \Validator::make($input, $rules);
    	if ($validator->fails()) {
      	 $messages = $validator->messages();
      	 return \Redirect::to("/photos/{$id}")->withErrors($messages);
    	} else {
      	$comment = ['text' => $input["text"], 'user_id' => Auth::user()->id];
      	$comment = new Comment($comment);
      	$photo = Photo::where('_id', $id)->first();
				if(!$photo) {
					$photo = Photo::where('_id', (int)$id)->first();
				}
      	$photo->comments()->save($comment);

        $user = Auth::user();

        EventLogger::printEventLogs($photo->id, 'insert_comment', ['comment_id' => $comment->id], 'Web');

        /*Envio de notificação*/

        Event::dispatch('comment.create', array($user, $photo));


        $this->checkCommentCount(5,'test');
        return \Redirect::to("/photos/{$id}");
      }
  }


// need to be modified
  private function checkCommentCount($number_comment, $badge_name){
      $user = Auth::user();
      if(($user->badges()->where('name', $badge_name)->first()) != null){
        return;
      }
      if (($user->comments->count()) == $number_comment){
        $badge=Badge::where('name', $badge_name)->first();
        $user->badges()->attach($badge);
      }
  }

  public function commentLike($id) {
    $comment = Comment::find($id);
    $user = Auth::user();

    \Event::dispatch('comment.liked', array($user, $comment));

    EventLogger::printEventLogs(null, 'like', ["target_type" => 'comentário', "target_id" => $id], 'Web');

    $like = Like::getFirstOrCreate($comment, $user);
    if (is_null($comment)) {
      return \Response::json('fail');
    }
    return \Response::json([
      'url' => \URL::to('/comments/' . $comment->id . '/' . 'dislike'),
      'likes_count' => $comment->likes->count()]);
  }

  public function commentDislike($id) {
    $comment = Comment::find($id);
    $user = Auth::user();

    \Event::dispatch('comment.disliked', array($user, $comment));

    $eventContent['target_type'] = 'comentário';
    $eventContent['target_id'] = $id;
    EventLogger::printEventLogs(null, 'dislike', $eventContent, 'Web');

    if (is_null($comment)) {
      return \Response::json('fail');
    }
    return \Response::json([
      'url' => \URL::to('/comments/' . $comment->id . '/' . 'like'),
      'likes_count' => $comment->likes->count()]);
  }
}
