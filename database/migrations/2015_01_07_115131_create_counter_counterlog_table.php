<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounterCounterlogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::create('counter_counterlog', function(Blueprint $table)
		// {
		// 	$table->bigInteger('counter_id')->unsigned();
		// 	$table->bigInteger('counterlog_id')->unsigned();
		// 	$table->foreign('counter_id')->references('id')->on('counter');
		// 	$table->foreign('counterlog_id')->references('id')->on('counterlog');
		// });
		/*
			Já existe um atributo 'counter_id' na tabela 'counterlog', a relação não é m:n, então esta tabela
			não é necessária
		*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::drop('counter_counterlog');
	}

}
