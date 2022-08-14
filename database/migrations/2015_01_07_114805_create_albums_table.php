<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('albums', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->dateTime('creationDate')->nullable();
			$table->string('description')->nullable();
			$table->string('title')->nullable();
			$table->bigInteger('cover_id')->nullable()->unsigned();
			$table->bigInteger('user_id')->nullable()->unsigned();
			$table->foreign('cover_id')->references('id')->on('photos');
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('albums');
	}

}
