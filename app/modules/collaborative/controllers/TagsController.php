<?php
namespace modules\collaborative\controllers;
use modules\collaborative\models\Tag;
use modules\institutions\models\Institution as Institution;
use Photo;
class TagsController extends \BaseController {

	public function index()
	{
		$tags = Tag::all();
		return $tags;
	}

	public function refreshCount() {
		$photos = Photo::all();
		echo "processando...\r\n";
		\DB::update('update tags set count = 0');
		foreach ($photos as $photo) {
			$photo_tags = $photo->tags;
			echo "Foto de ID = " . $photo->id . ":\r\n";
			foreach ($photo_tags as $tag) {
				$tag->count = $tag->count + 1;
				$tag->save();
				echo "Tag atualizada: " . $tag->name . "\r\n";
			}
		}
		$deleted = Photo::onlyTrashed()->get();
		foreach ($deleted as $photo) {
			\DB::table('tag_assignments')->where('photo_id', '=', $photo->id)->delete();
		}
	}

}
