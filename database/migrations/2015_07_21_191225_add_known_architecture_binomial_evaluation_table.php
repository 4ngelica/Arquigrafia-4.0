<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKnownArchitectureBinomialEvaluationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('binomial_evaluation', function ($table) {    		
    		$table->enum('knownArchitecture',['yes','no'])->default('no')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('binomial_evaluation', function ($table) {
    		$table->dropColumn('knownArchitecture');
		});
	}

}
