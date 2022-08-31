<?php
namespace App\modules\gamification\models;

use Carbon\Carbon;
use App\Models\Users\User;

class Badge extends \Eloquent {

	protected $fillable = ['name', 'image', 'description'];

	public function users()
	{
		return $this->belongsToMany('App\Models\Users\User','user_badges')
      ->withTimestamps()
      ->withPivot('element_type', 'element_id');
	}

	public function comments()
	{
		return $this->hasMany('Comment');
	}

	public function photos()
	{
		return $this->hasMany('Photo');
	}

	public function render()
	{
		$image = "./img/badges/".$this->image;
		print '<img id="badge_image" src="'.$image.'" alt="badge" />';
		print '<h3 id="badge_name">'.$this->name.'</h3>';
    print '<p>'.$this->description.'</p>';
	}

	public function scopeWhereNotRelatedToUser($query, $id)
  {
    $query->whereNotIn('id', function ($query) use ($id)
    {
      $query->select('badge_id')->from('user_badges')
        ->where('user_id', '=', $id);
    });
  }

  public static function getDestaqueDaSemana($photo) {
    $last_week = Carbon::today()->subWeek();
    if ( $photo->countLikesAfterDate($last_week) != 5 ) {
      return false;
    }
    $destaque_badge = static::whereName('Destaque da Semana')->first();
    $has_badge = $destaque_badge->withElement($photo)->count();
    if ( $has_badge ) {
      return false;
    } else {
      $destaque_badge->users()->attach($photo->user, array(
          'element_id' => $photo->id,
          'element_type' => get_class($photo),
        ));
      return $destaque_badge;
    }
  }

  public function withElement($el) {
    return $this->users()
      ->wherePivot('element_type', get_class($el))
      ->wherePivot('element_id', $el->id);
  }

}
