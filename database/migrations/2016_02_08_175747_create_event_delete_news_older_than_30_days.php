<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventDeleteNewsOlderThan30Days extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q = "CREATE EVENT `delete_old_news` ON SCHEDULE EVERY 1 DAY DO DELETE FROM news WHERE news.updated_at < (now() - INTERVAL 30 DAY)";		
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
		$q = "DROP EVENT IF EXISTS `delete_old_news`";		
		DB::connection()->getPdo()->exec($q);
	}

}
