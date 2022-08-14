<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyEventDeleteInactiveAccounts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q = "ALTER TABLE `leaderboards` DROP FOREIGN KEY `leaderboards_user_id_foreign`; ALTER TABLE `leaderboards` ADD CONSTRAINT `leaderboards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";		
		DB::unprepared($q);

		$q = "DROP EVENT IF EXISTS `delete_inactive_accounts`";		
		DB::unprepared($q);

		$q = "CREATE EVENT delete_inactive_accounts ON SCHEDULE EVERY 1 DAY ON COMPLETION PRESERVE DO DELETE FROM users WHERE active = 'no' AND users.created_at < (now() - INTERVAL 30 DAY)";		
		DB::unprepared($q);

		$q = "SET GLOBAL event_scheduler=`ON`";
		DB::unprepared($q);
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
