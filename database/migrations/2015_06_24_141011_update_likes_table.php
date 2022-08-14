<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */

	public function up()
	{
		//Like::truncate();
		Schema::drop('likes');
		Schema::create('likes', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('likable_id');
			$table->string('likable_type');
			$table->bigInteger('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
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
		Schema::table('likes', function (Blueprint $table) {
			$table->dropColumn('likable_id');
			$table->dropColumn('likable_type');
			$table->bigInteger('photo_id')->unsigned()->nullable();
			$table->bigInteger('comment_id')->unsigned()->nullable();
			$table->foreign('photo_id')->references('id')->on('photos');
			$table->foreign('comment_id')->references('id')->on('comments');
		});
	}

}
