<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstitutionToPhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('photos',function ($table) {
		$table->bigInteger('institution_id')->unsigned()->nullable();
		$table->foreign('institution_id')->references('id')->on('institutions');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('photos', function ($table) {
    		$table->dropColumn('institution_id');
		});
	}

}
