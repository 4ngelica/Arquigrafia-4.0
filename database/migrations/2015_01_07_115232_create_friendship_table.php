<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendshipTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('friendship', function(Blueprint $table)
		{
			$table->bigInteger('following_id')->unsigned();
			$table->bigInteger('followed_id')->unsigned();
			$table->foreign('following_id')->references('id')->on('users');
			$table->foreign('followed_id')->references('id')->on('users');
			// $table->bigIncrements('id');
			// $table->bigInteger('user_id')->unsigned();
			// $table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('friendship');
	}

}
