<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToSuggestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('suggestions', function(Blueprint $table)
		{
			$table->enum('type', ['review','edition'])->default('review')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('suggestions', function(Blueprint $table)
		{
			$table->dropColumn('type');
		});
	}

}
