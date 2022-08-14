<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBadgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('badges', function(Blueprint $table) {
		 	$table->bigIncrements('id');
		 	$table->string('name')->unique();
		 	$table->string('image');
		 	$table->text('description')->nullable();
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
		Schema::drop('badges');
	}

}

