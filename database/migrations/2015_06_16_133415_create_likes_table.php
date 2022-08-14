<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('likes', function(Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('photo_id')->unsigned()->nullable();
			$table->bigInteger('comment_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('photo_id')->references('id')->on('photos');
			$table->foreign('comment_id')->references('id')->on('comments');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('likes');
	}

}
