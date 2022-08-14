<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserBadgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_badges', function(Blueprint $table)
		{
			$table->bigInteger('element_id')->unsigned()->nullable();
			$table->string('element_type')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_badges', function(Blueprint $table)
		{
			$table->dropColumn('element_id');
			$table->dropColumn('element_type');
		});
	}

}
