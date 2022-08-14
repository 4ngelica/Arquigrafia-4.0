<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('object_type');
			$table->string('object_id');
			$table->bigInteger('user_id');
			$table->bigInteger('sender_id');
			$table->string('data');
			$table->string('news_type');
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
		Schema::table('news', function(Blueprint $table)
		{
			Schema::drop('news');
		});
	}

}
