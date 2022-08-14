<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinomialEvaluationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('binomial_evaluation', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('photo_id')->unsigned();
			$table->integer('evaluationPosition');
			$table->bigInteger('binomial_id')->nullable()->unsigned();
			$table->bigInteger('user_id')->nullable()->unsigned();
			$table->foreign('photo_id')->references('id')->on('photos');
			$table->foreign('binomial_id')->references('id')->on('binomials');
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
		Schema::drop('binomial_evaluation');
	}

}
