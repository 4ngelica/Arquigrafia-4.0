<?php
namespace modules\collaborative\models;
use modules\collaborative\models\Tag;
use Photo;
use User;
use modules\gamification\traits\LikableGamificationTrait;


class Comment extends \Eloquent {

	use LikableGamificationTrait;

	protected $fillable = ['text', 'user_id', 'photo_id'];

	public function photo()
	{
		return $this->belongsTo('Photo');
	}

	public function user()
	{
		return $this->belongsTo('User');
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