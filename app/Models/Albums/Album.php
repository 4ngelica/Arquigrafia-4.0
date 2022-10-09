<?php

namespace App\Models\Albums;

// use Jenssegers\Mongodb\Eloquent\Model as Model;
use Jenssegers\Mongodb\Eloquent\Model as Model;
use Illuminate\Support\Facades\Log;

class Album extends Model {

	protected $connection = 'mongodb';
	protected $collection = 'albums';
	public $timestamps = false;

	protected $fillable = ['creationDate', 'description', 'title', 'cover_id', 'user_id','institution_id'];

	protected $rules = [
		'title' => 'required'
	];

	public function photos()
	{
		return $this->belongsToMany('App\Models\Photos\Photo', 'album_elements');
	}

	public function user()
	{
		return $this->belongsTo('App\Models\Users\User');
	}

	public function onlyUser()
	{
		return $this->belongsTo('App\Models\Users\User')->whereNull('institution_id');
	}

	public function cover()
	{
		return $this->belongsTo('App\Models\Photos\Photo', 'cover_id');
	}

	public function institution()
	{
		return $this->belongsTo('App\Models\Institutions\Institution');
	}

	public function onlyInstitution()
	{
		return $this->belongsTo('App\Models\Institutions\Institution')->whereNull('user_id');
	}

	public function updateInfo($title, $description, $cover) {
		$this->title = $title;
		$this->description = $description;
		if (isset($cover)) {
			$this->cover()->associate($cover);
		}

	}

	public static function create(array $attr)
	{
		$album = new Album;
		$album->updateInfo($attr['title'], $attr['description'], $attr['cover']);
		$album->creationDate = date('Y-m-d H:i:s');
		$album->user()->associate($attr['user']);

		if ( array_key_exists('institution',$attr) && !empty($attr['institution']) ) {
			$album->institution()->associate($attr['institution']);
		}
		$album->save();
		return $album;
	}

	public function delete() {
		$this->detachPhotos();
		parent::delete();
	}

	public function detachPhotos($photos = array()) {
		if ($photos instanceof Photo) {
			$this->photos()->detach($photos->_id);
		} else {
			$this->photos()->detach($photos);
		}
	}

	public function attachPhotos($photos = array()) {
		Log::info("log of attachPhotos");
		if ($photos instanceof Photo) {
			$this->photos()->attach($photos->_id);
		} else {
			$this->photos()->attach($photos);
		}
	}

	public function syncPhotos($photos = array(), $delete = false) {
		if ($photos instanceof Photo) {
			$this->photos()->sync(array($photos->id), $delete);
		} else {
			$this->photos()->sync($photos, $delete);
		}
	}

	public function hasCover() {
		return !is_null($this->cover);
	}

	public function scopeWithInstitution( $query, $institution ) {
		$id = $institution instanceof Institution ? $institution->id : $institution;
		return $query->where('institution_id', $id);
	}

	public function scopeWithoutInstitutions($query) {
		return $query->whereNull('institution_id');
	}

}
