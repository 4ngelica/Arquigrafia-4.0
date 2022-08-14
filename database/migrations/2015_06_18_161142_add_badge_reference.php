<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBadgeReference extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('photos', function(Blueprint $table){

			$table-> bigInteger('badge_id')->nullable()->unsigned();
			$table->foreign('badge_id')->references('id')->on('badges');
		});

		Schema::table('comments', function(Blueprint $table){

			$table-> bigInteger('badge_id')->nullable()->unsigned();
			$table->foreign('badge_id')->references('id')->on('badges');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
