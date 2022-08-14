<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoAuthorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('photo_author', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('photo_id')->unsigned();
			$table->bigInteger('author_id')->unsigned(); 
			$table->foreign('photo_id')->references('id')->on('photos');
			$table->foreign('author_id')->references('id')->on('authors'); 			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('photo_author');
	}

}
