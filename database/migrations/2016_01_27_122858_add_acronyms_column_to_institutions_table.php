<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcronymsColumnToInstitutionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('institutions', function(Blueprint $table)
		{
			$table->string('acronym')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('institutions', function(Blueprint $table)
		{
			if (Schema::hasColumn('institutions', 'acronym')) {
				$table->dropColumn('acronym');
			}
		});
	}

}
