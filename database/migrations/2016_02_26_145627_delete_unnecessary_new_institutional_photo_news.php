<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteUnnecessaryNewInstitutionalPhotoNews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q = "DELETE FROM news WHERE news_type = 'new_institutional_photo' AND updated_at <> (SELECT updated
						   FROM (SELECT max(updated_at) as updated
                           		 FROM news
                    	   		 WHERE news_type = 'new_institutional_photo') as c)";
		DB::insert(DB::raw($q));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		
	}

}
