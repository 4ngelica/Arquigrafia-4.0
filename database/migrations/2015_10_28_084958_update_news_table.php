<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( Schema::hasColumn('news', 'data') )
			Schema::table('news', function(Blueprint $table)
			{
				$table->dropColumn('data');
			});
		Schema::table('news', function ($table) {    		
    		$table->string('data')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('news', function ($table) {
    		$table->dropColumn('data');
		});
	}

}
