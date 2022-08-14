<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToNews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q = "ALTER TABLE news MODIFY COLUMN user_id int(10) unsigned NOT NULL;";
		DB::unprepared(DB::raw($q));
		$q = "ALTER TABLE news MODIFY COLUMN sender_id int(10) unsigned NOT NULL;";
		DB::unprepared(DB::raw($q));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$q = "ALTER TABLE news MODIFY COLUMN user_id bigint(20) NOT NULL;";
		DB::insert(DB::raw($q));
		$q = "ALTER TABLE news MODIFY COLUMN sender_id bigint(20) NOT NULL;";
		DB::insert(DB::raw($q));
	}

}
