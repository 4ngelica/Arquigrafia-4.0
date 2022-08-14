<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDraftColumnToPhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasColumn('photos', 'draft'))
		{
			Schema::table('photos', function(Blueprint $table)
			{
				$table->timestamp('draft')->nullable()->default(null);
			});
		}
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
			if (Schema::hasColumn('photos', 'draft')) {
				$table->dropColumn('draft');
			}
		});
	}

}
