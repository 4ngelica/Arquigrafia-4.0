<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanBadgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('badges', function(Blueprint $table)
		{
			if ( Schema::hasColumn('badges', 'class') ) {
				$table->dropColumn('class');
			}
			if ( ! Schema::hasColumn('badges', 'image') ) {
				$table->string('image')->nullable();
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('badges', function(Blueprint $table)
		{
			$table->enum('class', array('Gold', 'Silver', 'Bronze'))->default('Bronze');
		});
	}

}
