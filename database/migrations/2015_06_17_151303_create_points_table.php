<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scores', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->bigInteger('user_id')->unsigned();
		 		$table->foreign('user_id')->references('id')->on('users');
		 		$table->bigInteger('points')->default(0);


			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('scores');
	}

}
