<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->dateTime('postDate')->nullable();
			$table->string('text')->nullable();
      $table->timestamps();
			$table->bigInteger('user_id')->nullable()->unsigned();
			$table->bigInteger('photo_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
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
		Schema::drop('comments');
	}

}
