<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFieldReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('reports', function(Blueprint $table){
			$table->dropColumn('data_type');
			$table->string('report_type_data');
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
			$table->dropColumn('report_type_data');
			$table->enum('data_type', array('Title','Tag','Image','Author','Address','Description'));
		});
	}

}
