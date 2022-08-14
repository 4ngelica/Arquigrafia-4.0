<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsToModeration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('suggestions', function(Blueprint $table){
			$table->timestamps();
		});
		Schema::table('moderators', function(Blueprint $table){
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('suggestions', function($table){
			$table->dropColumn('created_at');
		  $table->dropColumn('updated_at');
		});
		Schema::table('moderators', function($table){
			$table->dropColumn('created_at');
		  $table->dropColumn('updated_at');
		});
	}

}
