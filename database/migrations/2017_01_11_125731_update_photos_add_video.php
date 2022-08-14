<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePhotosAddVideo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('photos', function(Blueprint $table) {
			$table->string('video')->nullable();
			$table->string('type')->nullable();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('photos', function(Blueprint $table) {
			if( Schema::hasColumn('photos', 'video') )
				$table->dropColumn('video');
			if( Schema::hasColumn('photos', 'type') )
				$table->dropColumn('type');
		});
	}

}
