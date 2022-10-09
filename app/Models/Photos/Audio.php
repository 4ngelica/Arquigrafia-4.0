<?php

namespace App\Models\Photos;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Audio extends Model {
  protected $connection = 'mongodb';
  protected $collection = 'audios';

  protected $table = 'audios';

  protected $fillable = ['file', 'name', 'description', 'user_id', 'photo_id'];

  public function photo() {
    return $this->belongsTo('App\Models\Photos\Photo');
  }

  public function user() {
    return $this->belongsTo('App\Models\Users\User');
  }

}
