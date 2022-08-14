<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('external_accounts', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('accessToken')->nullable();
			$table->integer('accountType')->nullable();
			$table->string('tokenSecret')->nullable();
			$table->bigInteger('user_id')->nullable()->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('external_accounts');
	}

}
