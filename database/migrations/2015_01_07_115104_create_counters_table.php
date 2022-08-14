<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('counters', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->dateTime('dataCriacao')->nullable();
			$table->integer('value');
			$table->bigInteger('photo_id')->unsigned();
			$table->foreign('photo_id')->references('id')->on('photos');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('counters');
	}

}
