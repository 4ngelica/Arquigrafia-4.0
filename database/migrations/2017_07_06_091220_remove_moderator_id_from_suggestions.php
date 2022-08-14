<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveModeratorIdFromSuggestions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('suggestions', function($table){
			$table->dropForeign(['moderator_id']);
		  $table->dropColumn('moderator_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('suggestions', function(Blueprint $table){
			$table->bigInteger('moderator_id')->unsigned();
		});
	}

}
