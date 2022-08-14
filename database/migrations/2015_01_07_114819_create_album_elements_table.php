<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumElementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('album_elements', function(Blueprint $table)
		{
			$table->bigInteger('album_id')->unsigned();
			$table->bigInteger('photo_id')->unsigned();
			$table->foreign('album_id')->references('id')->on('albums');
			$table->foreign('photo_id')->references('id')->on('photos');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('album_elements');
	}

}
