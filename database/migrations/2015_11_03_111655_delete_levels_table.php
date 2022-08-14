<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteLevelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('levels');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('levels', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('level')->unsigned();
			$table->bigInteger('minXP')->unsigned();
			$table->string('title')->nullable();
		});

	}

}
