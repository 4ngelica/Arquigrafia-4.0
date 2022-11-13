<?php

namespace App\Models\Collaborative;

use App\Models\Collaborative\Tag;
use App\Models\Photos\Photo;
use App\Models\Users\User;
use App\Traits\Gamification\LikableGamificationTrait;
use Jenssegers\Mongodb\Eloquent\Model as Model;


class Comment extends Model {

	use LikableGamificationTrait;
	protected $connection = 'mongodb';
	protected $collection = 'comments';
	protected $fillable = ['text', 'user_id', 'photo_id'];

	public function photo()
	{
		return $this->belongsTo('App\Models\Photos\Photo');
	}

	public function user()
	{
		return $this->belongsTo('App\Models\Users\User');
	}

  public static function createCommentsMessage($commentsCount)
  {
    	$commentsMessage = '';
    	if($commentsCount == 0)
    	  $commentsMessage = 'Ninguém comentou ainda esta imagem';
    	else if($commentsCount == 1)
      	$commentsMessage = 'Existe ' . $commentsCount . ' comentário sobre esta imagem';
    	else
      	$commentsMessage = 'Existem '. $commentsCount . ' comentários sobre esta imagem';
    	return $commentsMessage;
  }
  public function likable()
  {
    return $this->morphTo();
  }

}
