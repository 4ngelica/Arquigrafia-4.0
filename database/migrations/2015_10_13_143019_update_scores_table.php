<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( Schema::hasColumn('scores', 'points') )
			Schema::table('scores', function(Blueprint $table)
			{
				$table->dropColumn('points');
			});
		Schema::table('scores', function(Blueprint $table)
		{
			$table->bigInteger('points')->unsigned()->default(0);
			$table->integer('level_id')->unsigned()->nullable();
			$table->foreign('level_id')->references('id')->on('levels');
		});
		DB::insert('insert into scores (points, user_id) (select 0, id from users where id not in (select user_id from scores))');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasColumn('scores', 'level_id'))
			Schema::table('scores', function(Blueprint $table)
			{
				$table->dropForeign('scores_level_id_foreign');
				$table->dropColumn('level_id');
			});
	}

}
