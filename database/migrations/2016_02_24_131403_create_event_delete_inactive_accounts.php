<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventDeleteInactiveAccounts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q = "CREATE EVENT `delete_inactive_accounts` ON SCHEDULE EVERY 30 DAY DO DELETE FROM users WHERE active = `no`";		
		DB::connection()->getPdo()->exec($q);

		$q = "SET GLOBAL event_scheduler=`ON`";
		DB::connection()->getPdo()->exec($q);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$q = "DROP EVENT IF EXISTS `delete_inactive_accounts`";		
		DB::connection()->getPdo()->exec($q);
	}

}
