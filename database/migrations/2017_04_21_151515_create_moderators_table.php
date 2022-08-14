<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModeratorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('moderators', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('moderation_type_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('moderation_type_id')->references('id')->on('moderation_types');
			$table->string('level');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('moderators');
	}

}
