<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('photos', function(Blueprint $table)
		{
			$table->boolean('authorized')->default(true);
			$table->dropForeign('photos_badge_id_foreign');
			$table->dropColumn('badge_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('photos', function(Blueprint $table)
		{
			$table->dropColumn('authorized');
			$table->bigInteger('badge_id')->nullable()->unsigned();
			$table->foreign('badge_id')->references('id')->on('badges');
		});
	}

}
