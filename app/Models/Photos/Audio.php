<?php

namespace App\Models\Photos;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model {

  protected $table = 'audios';

  protected $fillable = ['file', 'name', 'description', 'user_id', 'photo_id'];

  public function photo() {
    return $this->belongsTo('App\Models\Photos\Photo');
  }

  public function user() {
    return $this->belongsTo('App\Models\Users\User');
  }

}
