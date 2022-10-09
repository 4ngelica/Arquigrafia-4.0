<?php
namespace App\Models\News;
use App\Models\Collaborative\Comment as Comment;
use App\Models\Photos\Photo;
use  App\Models\Users\User;
use Log;
use Auth;
use \Session;
use Illuminate\Support\Collection as Collection;
use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class News extends Model {
	protected $table = 'news';
	protected $fillable = array('object_type', 'object_id', 'user_id', 'sender_id', 'news_type');

	public function user()
  {
        return $this->belongsTo('App\Models\Users\User');
  }

  public static function registerPhotoInstitutional($photo, $type)
  {
      $institutional_news = Static::user0NewsPhoto($photo,$type)->get();

        foreach ($institutional_news as $note)
        {
          if($note->news_type == $type &&
          	 $note->sender_id == Session::get('institutionId')) {

             	$curr_note = $note;
            }
        }
        if(isset($curr_note)) {
        	\Log::info("Update");
        	$currentNews = Static::specificNews($curr_note->id)->first();
        	$currentNews->updateCurrentNews($photo);
        }else {
          \Log::info("Create");
        	Static::createNews('Photo',$photo->id,0,$photo->institution_id,$type);
        }

  }

  public static function registerPhotoEvaluated($evaluate,$type)
  {
        $user = User::find($evaluate->user_id);
        foreach ($user->followers as $users) {
            foreach ($users->news as $news) {
                if ($news->news_type == $type && $news->object_id == $evaluate->photo_id) {
                      $last_news = $news;
                      $primary = $type;
                }else if ($news->news_type == 'liked_photo' || $news->news_type == 'commented_photo') {
                    if ($news->object_id == $evaluate->photo_id) {
                          $last_news = $news;
                          $primary = 'other';
                    }else {
                        $comment = Comment::find($news->object_id);
                        if(!is_null($comment)) {
                          if ($comment->photo_id == $evaluate->photo_id) {
                                $last_news = $news;
                                $primary = 'other';
                           }
                        }
                    }
                }
            }
            if (isset($last_news)) {
                    $last_update = $last_news->updated_at;
                    if($last_update->diffInDays(Carbon::now('America/Sao_Paulo')) < 7) {
                        if ($news->sender_id == $user->id) {
                            $already_sent = true;
                        }else if ($news->data != null) {
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
                                $last_news->secondary_type = $type;
                            }else if ($last_news->tertiary_type == null) {
                                $last_news->tertiary_type = $type;
                            }
                            $last_news->save();
                        }
                    }else {
                          Static::createNews('Photo',$evaluate->photo_id,$users->id,$user->id,$type);
                    }
            }else {
                      Static::createNews('Photo',$evaluate->photo_id,$users->id,$user->id,$type);
            }
        }
  }

  public static function eventLikedPhoto($likes, $type)
  {
        $user = Auth::user();
        foreach ($user->followers as $users)
        {
            foreach ($users->news as $news) {
              if ($news->news_type == $type) {
                if ($news->object_id == $likes->likable_id) {
                  $last_news = $news;
                  $primary = $type;
                }
              }
              else if ($news->news_type == $type) {
                if ($news->object_id == $likes->likable_id) {
                    $last_news = $news;
                    $primary = 'other';
                }
              }
              else if ($news->news_type == 'commented_photo') {
                $comment = Comment::find($news->object_id);
                if(!is_null($comment)) {
                  if ($comment->photo_id == $likes->likable_id) {
                    $last_news = $news;
                    $primary = 'other';
                  }
                }
              }
            }
            if (isset($last_news))
            {
                $last_update = $last_news->updated_at;
                  if($last_update->diffInDays(Carbon::now('America/Sao_Paulo')) < 7) {
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
                        $last_news->secondary_type = $type;
                      }
                      else if ($last_news->tertiary_type == null) {
                        $last_news->tertiary_type = $type;
                      }
                      $last_news->save();
                    }
                  } else {
                    Static::createNews('Photo',$likes->likable_id,$users->id,$user->id,$type);
                  }
            }else {
              Static::createNews('Photo',$likes->likable_id,$users->id,$user->id,$type);
            }

        }
  }


  public static function eventCommentedPhoto($comment, $type)
  { $user = Auth::user();
    foreach ($user->followers as $users)
    {
        foreach ($users->news as $news) {   \Log::info("tipoComment=".$news->object_id);
          if ($news->news_type == $type && Comment::find($news->object_id)->photo_id == $comment->photo_id) {
              $last_news = $news;
              $primary = $type;
          }else if ($news->news_type == 'evaluated_photo' || $news->news_type == 'liked_photo') {
              if ($news->object_id == $comment->photo_id) {
                $last_news = $news;
                $primary = 'other';
              }
          }
        }
        if (isset($last_news)) {
            $last_update = $last_news->updated_at; \Log::info("compar=".$comment->photo_id);
            if($last_update->diffInDays(Carbon::now('America/Sao_Paulo')) < 7) {
                if ($news->sender_id == $user->id) {
                    $already_sent = true; \Log::info("compar=".$comment->photo_id);
                }else if ($news->data != null) {
                    $data = explode(":", $news->data);
                    for($i = 1; $i < count($data); $i++) {
                        if($data[$i] == $user->id) {
                            $already_sent = true;
                        }
                    } \Log::info("nonull=".$comment->photo_id);
                }
                if (!isset($already_sent)) {
                    $data = $last_news->data . ":" . $user->id;
                    $last_news->data = $data;
                    $last_news->save(); \Log::info("ja=".$comment->photo_id);
                }
                if ($primary == 'other') {
                    if ($last_news->secondary_type == null) {
                        $last_news->secondary_type = $type;
                    }else if ($last_news->tertiary_type == null) {
                        $last_news->tertiary_type = $type;
                    } \Log::info("other=".$comment->photo_id);
                    $last_news->save();
                }
            } else { \Log::info("mesmo=".$comment->photo_id);
                Static::createNews('Comment',$comment->id,$users->id,$user->id,$type);
            }
        }else {        \Log::info("come=".$comment->photo_id);
            Static::createNews('Comment',$comment->id,$users->id,$user->id,$type);
            \Log::info("saved=".$comment->photo_id);
        }
    }
  }

  public static function eventNewPicture($user, $type)
  {
      foreach ($user->followers as $users)
      {
           foreach ($users->news as $note) {
              if($note->news_type == $type && $note->sender_id == $user->id) {
                $curr_note = $note;
              }
           }
           if(isset($curr_note)) {
              if($note->sender_id == $user->id) {
                $date = $curr_note->created_at;
                if($date->diffInDays(Carbon::now('America/Sao_Paulo')) > 7) {
                   Static::createNews('User',$user->id,$users->id,$user->id,$type);
                }
             }
           }else {
                   Static::createNews('User',$user->id,$users->id,$user->id,$type);
           }
      }
  }

  public static function eventUpdateProfile($user, $type)
  {
      foreach ($user->followers as $users)
      {
          foreach ($users->news as $note) {
              if($note->news_type == $type && $note->sender_id == $user->id) {
                  $curr_note = $note;
              }
          }
          if(isset($curr_note)) {
              if($note->sender_id == $user->id) {
                  $date = $curr_note->created_at;
                  if($date->diffInDays(Carbon::now('America/Sao_Paulo')) > 7) {
                     Static::createNews('User',$user->id,$users->id,$user->id,$type);
                  }
              }
          } else {
              Static::createNews('User',$user->id,$users->id,$user->id,$type);
          }
      }
  }

  public static function eventNewPhoto($photo, $type)
  {  $userObject=User::find($photo->user_id);
      foreach($userObject->followers as $users)
      {
          foreach ($users->news as $note) {
            if($note->news_type == $type && $note->sender_id == $userObject->id) {
              $curr_note = $note;
            }
          }
          if(isset($curr_note)) {
              $date = $curr_note->created_at;
              if($date->diffInDays(Carbon::now('America/Sao_Paulo')) > 7) {
                  Static::createNews('Photo', $photo->id,$users->id,$userObject->id,$type);
              }else {
                $curr_note->object_id = $photo->id;
                $curr_note->save();

              }
          }else {
            Static::createNews('Photo', $photo->id,$users->id,$userObject->id,$type);
          }
      }
  }

  public static function eventUpdatePhoto($photo, $type)
  {
      $user = User::find($photo->user_id);
      foreach ($user->followers as $users) {
          foreach ($users->news as $note) {
            if($note->news_type == $type && $note->sender_id == $user->id) {
                $curr_note = $note;
            }
          }
          if(isset($curr_note)) {
            if($curr_note->sender_id == $user->id){
              //if($note->sender_id == $user->id) {
                $date = $curr_note->created_at;
                if($date->diffInDays(Carbon::now('America/Sao_Paulo')) > 7)
                    Static::createNews('Photo', $photo->id, $users->id, $user->id, $type);
            }
          }else {
            Static::createNews('Photo', $photo->id, $users->id, $user->id, $type);
          }
      }
  }

  public static function eventGetFacebookPicture($user, $type)
  {
      foreach ($user->followers as $users) {
          foreach ($users->news as $note) {
            if($note->news_type == $type && $note->sender_id == $user->id) {
              $curr_note = $note;
            }
          }
          if(isset($curr_note)) {
            if($note->sender_id == $user->id) {
              $date = $note->created_at;
              if($date->diffInDays(Carbon::now('America/Sao_Paulo')) > 7) {
                  Static::createNews('User', $user->id, $users->id, $user->id, $type);
              }
            }
          } else {
             Static::createNews('User', $user->id, $users->id, $user->id, $type);
          }
      }
  }

  public static function createNews($objectType, $objectId, $userId, $senderId, $type)
  {
		$news = new News();
		$news->object_type = $objectType;
    $news->object_id = $objectId;
    $news->user_id = $userId;
    $news->sender_id = $senderId;
    $news->news_type = $type;
    $news->save();
	}

  public function updateCurrentNews($photo)
  {   \Log::info("photo_id".$photo->id);
    	$this->object_id = $photo->id;
      $this->updated_at = Carbon::now('America/Sao_Paulo');
      $this->save();
  }

  public function scopeUser0NewsPhoto($query, $photo, $type)
  {   	return $query->where('user_id', '=', 0)
					->where('news_type', '=',$type );
  }

  public static function scopeSpecificNews($query, $currentId)
  {
			return $query->where('id', '=', $currentId);
  }


  public static function deletePhotoActivities($photo,$type)
  {
    $all_users = User::all();
    foreach ($all_users as $users) {
      foreach ($users->news as $news) {
        if ($news->news_type == 'liked_photo' || $news->news_type == 'highlight_of_the_week' ||
           $news->news_type == 'edited_photo' || $news->news_type == 'evaluated_photo' || $news->news_type == 'new_institutional_photo' || $news->news_type == 'new_photo') {
          if ($news->object_id == $photo->id) {
              $news->delete();
          }
        }
        if ($news->news_type == 'commented_photo') {
            $object_comment = Comment::find($news->object_id);
           if (!is_null($object_comment)) {

                    if( $object_comment->photo_id  == $photo->id ){

                        News::where('id','=', $news->id )
                        ->where('news_type', $news->news_type)
                        ->delete();
                        $news->delete();

                    }
           }

        } //commented
      }
    }
  }



}
