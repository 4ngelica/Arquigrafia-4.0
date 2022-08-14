<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendshipInstitutionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('friendship_institution', function(Blueprint $table)
		{
			$table->bigInteger('following_user_id')->unsigned();
			$table->bigInteger('institution_id')->unsigned();
			$table->foreign('following_user_id')->references('id')->on('users');
			$table->foreign('institution_id')->references('id')->on('institutions');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('friendship_institution');
	}

}
