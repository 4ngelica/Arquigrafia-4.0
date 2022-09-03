<?php namespace lib\gamification\controllers;

use lib\utils\ActionUser;
use lib\gamification\models\Badge;
use lib\gamification\models\Like;
use Notification;
use Carbon\Carbon;

class LikesController extends Controller {

  public function photolike($id) {
    $photo = \Photo::find($id);
    $user = \Auth::user();
    if (is_null($photo)) {
      return \Response::json('fail');
    }
    $this->logLikeDislike($user, $photo, "a foto", "Curtiu", "user");
    if ($user->id != $photo->user_id) {
      $user_note = \User::find($photo->user_id);
      /*News feed*/
      foreach ($user->followers as $users) {
        foreach ($users->news as $news) {
          if ($news->news_type == 'liked_photo') {
            if($news->sender_id == $user->id) {
              $last_news = $news;
              $primary = 'liked_photo';
            }
          }
          else if ($news->news_type == 'evaluated_photo') {
            if($news->object_id == $id) {
              $last_news = $news;
              $primary = 'other';
            }
          }
          else if ($news->news_type == 'commented_photo') {
            $comment = \Comment::find($news->object_id);
            if(!is_null($comment)) {
              if($comment->photo_id == $id) {
                $last_news = $news;
                $primary = 'other';
              }
            }
          }
        } 
        if (isset($last_news)) {
          $last_update = $last_news->updated_at;
          if ($news->sender_id == $user->id) {
            $already_sent = true;
          }
          else if ($news->data != null) {
            $data = explode(":", $news->data);
            for($i = 1; $i < count($data); $i++) {
              if($data[$i] == $user->id) {
                $already_sent = true;
              }
            }
          }
          if (!isset($already_sent)) {
            $data = $last_news->data . ":" . $user->id;
            $last_news->data = $data;
            $last_news->save();
          }
          if ($primary == 'other') {
            if ($last_news->secondary_type == null) {
              $last_news->secondary_type = 'liked_photo';
            }
            else if ($last_news->tertiary_type == null) {
              if ($last_news->secondary_type != 'liked_photo')
                  $last_news->tertiary_type = 'liked_photo';
            }
            $last_news->save();
          }
          else if($primary == 'liked_photo') {
            if ($last_news->secondary_type == null && $last_news->tertiary_type == null) {
            \DB::table('news')->where('id', $last_news->id)->update(array('object_id' => $id, 'updated_at' => Carbon::now('America/Sao_Paulo')));
            }
            else {
            \News::create(array('object_type' => 'Photo', 
                              'object_id' => $id, 
                              'user_id' => $users->id, 
                              'sender_id' => $user->id, 
                              'news_type' => 'liked_photo'));
            }
          }
        }
        else {
          \News::create(array('object_type' => 'Photo', 
                              'object_id' => $id, 
                              'user_id' => $users->id, 
                              'sender_id' => $user->id, 
                              'news_type' => 'liked_photo'));
        }
      }
      \Notification::create('photo_liked', $user, $photo, [$user_note], null);
    }
    $like = Like::getFirstOrCreate($photo, $user);
    if ( ($badge = Badge::getDestaqueDaSemana($photo)) ) {
      \Notification::create('badge_earned', $user, $badge, [$photo->user], null);
    }
    return \Response::json([ 
      'url' => \URL::to('/photos/' . $photo->id . '/' . 'dislike'),
      'likes_count' => $photo->likes->count()
    ]);
  }

  public function photodislike($id) {
    $photo = \Photo::find($id);
    $user = \Auth::user();
    $this->logLikeDislike($user, $photo, "a foto", "Descurtiu", "user");
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
      'url' => \URL::to('/photos/' . $photo->id . '/' . 'like'),
      'likes_count' => $photo->likes->count()]);
  }

  public function commentdislike($id) {
    $comment = \Comment::find($id);
    $user = \Auth::user();
    $source_page = \Request::header('referer');
    $this->logLikeDislike($user, $comment, "o comentário", "Descurtiu", "user");
    try {
      $like = Like::fromUser($user)->withLikable($comment)->first();
      $like->delete();
    } catch (Exception $e) {
      //
    }
    if (is_null($comment)) {
      return \Response::json('fail');
    }
    return \Response::json([ 
      'url' => \URL::to('/comments/' . $comment->id . '/' . 'like'),
      'likes_count' => $comment->likes->count()]);
  }

  public function commentlike($id) {
    $comment = \Comment::find($id);
    $user = \Auth::user();
    $this->logLikeDislike($user, $comment, "o comentário", "Curtiu", "user");
    
    if ($user->id != $comment->user_id) {
      $user_note = \User::find($comment->user_id);
      \Notification::create('comment_liked', $user, $comment, [$user_note], null);
    }

    $like = Like::getFirstOrCreate($comment, $user);
    if (is_null($comment)) {
      return \Response::json('fail');
    }
    return \Response::json([ 
      'url' => \URL::to('/comments/' . $comment->id . '/' . 'dislike'),
      'likes_count' => $comment->likes->count()]);
  }

  private function logLikeDislike($user, $likable, $photo_or_comment, $like_or_dislike, $user_or_visitor) {
    $source_page = \Request::header('referer');
    ActionUser::printLikeDislike($user->id, $likable->id, $source_page, $photo_or_comment, $like_or_dislike, $user_or_visitor);
  }
}