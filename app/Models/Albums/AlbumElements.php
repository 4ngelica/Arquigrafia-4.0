<?php

namespace App\Models\Albums;

// use Jenssegers\Mongodb\Eloquent\Model as Model;
use Jenssegers\Mongodb\Eloquent\Model as Model;
use Illuminate\Support\Facades\Log;

class AlbumElements extends Model {

	protected $connection = 'mongodb';
	protected $collection = 'album_elements';
	public $timestamps = false;

	protected $fillable = ['photo_id', 'album_id'];
}
