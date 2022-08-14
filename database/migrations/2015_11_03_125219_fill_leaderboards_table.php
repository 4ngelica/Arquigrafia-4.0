<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillLeaderboardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q  = "insert into leaderboards (type, user_id, count, created_at, updated_at) ";
		$q .= "(select 'uploads', u.id, count(p.user_id), now(), now() from users u left join photos p ";
		$q .= "on u.id = p.user_id and p.deleted_at is null and p.institution_id is null group by u.id)";
		DB::insert(DB::raw($q));

		$q  = "insert into leaderboards (type, user_id, count, created_at, updated_at) ";
		$q .= "(select 'evaluations', u.id, count(b.user_id) / 6, now(), now() from users u left join binomial_evaluation b on ";
		$q .= "u.id = b.user_id and b.photo_id in (select id from photos where deleted_at is null) group by u.id)";
		DB::insert(DB::raw($q));			
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::delete(DB::raw('delete from leaderboards'));
	}

}
