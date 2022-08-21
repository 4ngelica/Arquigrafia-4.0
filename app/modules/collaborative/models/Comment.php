<?php
namespace App\modules\collaborative\models;

use modules\collaborative\models\Tag;
use App\Models\Photos\Photo;
use App\Models\Users\User;
use App\modules\gamification\traits\LikableGamificationTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;


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
