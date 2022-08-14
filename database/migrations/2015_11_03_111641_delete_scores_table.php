<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('scores');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('scores', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id')->unsigned();
	 		$table->foreign('user_id')->references('id')->on('users');
			$table->bigInteger('points')->unsigned()->default(0);
			$table->integer('level_id')->unsigned()->nullable();
			$table->foreign('level_id')->references('id')->on('levels');
		});
	}

}
