<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClearNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q = "delete from news";		
		DB::insert(DB::raw($q));

		$q  = "insert into news (object_type, object_id, user_id, sender_id, news_type, created_at, updated_at) ";
		$q .= " values ('Leaderboard', 0, 0, 0, 'check_leaderboard', now(), now())";		
		DB::insert(DB::raw($q));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$q = "delete from news";		
		DB::insert(DB::raw($q));
	}

}
