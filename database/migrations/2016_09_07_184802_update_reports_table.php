<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('reports', function(Blueprint $table){
			$table->dropColumn('type');
			$table->enum('data_type', array('Title','Tag','Image','Author','Address','Description'));
			//$table->string('data_type');
			$table->string('report_type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('reports', function(Blueprint $table){
			$table->dropColumn('data_type');
			$table->dropColumn('report_type');
			$table->string('type');
		});
	}

}
