<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNewsTableType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( Schema::hasColumn('news', 'secondary_type') )
			Schema::table('news', function(Blueprint $table)
			{
				$table->dropColumn('secondary_type');
			});
		Schema::table('news', function ($table) {    		
    		$table->string('secondary_type')->nullable();
		});
		if ( Schema::hasColumn('news', 'tertiary_type') )
			Schema::table('news', function(Blueprint $table)
			{
				$table->dropColumn('tertiary_type');
			});
		Schema::table('news', function ($table) {    		
    		$table->string('tertiary_type')->nullable();
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
    		$table->dropColumn('secondary_type');
		});
		Schema::table('news', function ($table) {
    		$table->dropColumn('tertiary_type');
		});
	}

}
