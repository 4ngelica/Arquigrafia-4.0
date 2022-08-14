<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorTextToPhotoAuthorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// $authorsPhotos = Photo::where('workAuthor', '<>', ' ')
    //                   	->whereNotNull('workAuthor')->get();
    // 	foreach ($authorsPhotos as $photos) {
    //     	if(strpos($photos->workAuthor,";")!== false){
    //         	$arrayAuthors = explode(";", $photos->workAuthor);
		//
    //         	foreach ($arrayAuthors as $arrayAuthor) {
    //           		$author = Author::where('name','=',trim($arrayAuthor))->first();
    //           		if(is_null($author)){
    //             		$author = new Author();
    //             		$author->name = mb_strtolower(trim($arrayAuthor));
    //             		$author->save();
    //           		}
    //           		$photos->authors()->attach($author->id);
    //         	}
    //     	}else{
    //         	$author = Author::where('name','=',trim($photos->workAuthor))->first();
    //         	if(is_null($author)){
    //             	$author = new Author();
    //             	$author->name = mb_strtolower(trim($photos->workAuthor));
    //             	$author->save();
    //         	}
    //         	$photos->authors()->attach($author->id);
    //    		}
    // 	}
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// $allPhotos = Photo::where('workAuthor', '<>', ' ')
    //                   ->whereNotNull('workAuthor')->get();
		//
    // 	foreach ($allPhotos as $photos) {
    //     	if(strpos($photos->workAuthor,";")!== false){
    //         	$arrayAuthors = explode(";", $photos->workAuthor);
    //         	foreach ($arrayAuthors as $arrayAuthor) {
    //             	$author = Author::where('name','=',trim($arrayAuthor))->first();
		//
    //             	if(!is_null($author)){
    //                 	$result = DB::table('photo_author')
    //                       ->where('photo_id', $photos->id)
    //                       ->where('author_id', $author->id)
    //                       ->delete();
    //             	}
    //         	}
    //     	}else{
    //         	$author = Author::where('name','=',trim($photos->workAuthor))->first();
		//
    //         	if(!is_null($author)){
    //             	$result = DB::table('photo_author')
    //                   ->where('photo_id', $photos->id)
    //                   ->where('author_id', $author->id)
    //                   ->delete();
    //         	}
    //     	}
    // 	}
	}

}
