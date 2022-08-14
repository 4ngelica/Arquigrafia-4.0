<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillDefaultNews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q  = "insert into news (object_type, object_id, user_id, sender_id, news_type, created_at, updated_at) ";
		$q .= "(select 'Intitution', 1, id, 1, 'check_institution', now(), now() from users)";		
		DB::insert(DB::raw($q));

		$q  = "insert into news (object_type, object_id, user_id, sender_id, news_type, created_at, updated_at) ";
		$q .= "(select 'Evaluation', (select photo_id from binomial_evaluation where id >= all (select id from binomial_evaluation)), id, 0, 'check_evaluation', now(), now() from users)";		
		DB::insert(DB::raw($q));

		$q  = "insert into news (object_type, object_id, user_id, sender_id, news_type, created_at, updated_at) ";
		$q .= "(select 'Leaderboard', 0, id, 0, 'check_leaderboard', now(), now() from users)";		
		DB::insert(DB::raw($q));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::delete(DB::raw("delete from news where news_type = 'check_institution' or news_type = 'check_evaluation' or news_type = 'check_leaderboard'"));
	}

}
