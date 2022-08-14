<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorizedToPhotos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Check if the table photos has the authorized column
		if(!Schema::hasColumn('photos', 'authorized')) {
			Schema::table('photos', function(Blueprint $table){
				$table->boolean('authorized')->default(true);
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
		Schema::table('photos', function($table){
		  $table->dropColumn('authorized');
		});
	}

}
