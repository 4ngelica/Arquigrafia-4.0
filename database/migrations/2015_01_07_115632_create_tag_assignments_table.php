<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagAssignmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tag_assignments', function(Blueprint $table)
		{
			$table->bigInteger('tag_id')->unsigned();
			$table->bigInteger('photo_id')->unsigned();
			$table->foreign('tag_id')->references('id')->on('tags');
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
		Schema::drop('tag_assignments');
	}

}
