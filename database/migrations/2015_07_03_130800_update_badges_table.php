<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBadgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('badges', function(Blueprint $table){
			$table->dropColumn('image');
			$table->enum('class', array('Gold', 'Silver', 'Bronze'))->default('Bronze');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('badges', function(Blueprint $table){
			$table->dropColumn('class');
			$table->string('image');
		});
	}

}
