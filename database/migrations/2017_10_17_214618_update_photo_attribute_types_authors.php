<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePhotoAttributeTypesAuthors extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('photo_attribute_types', function(Blueprint $table)
		{
      // Updating project_author
      $q = "update photo_attribute_types set attribute_type='authors' where attribute_type='project_author'";
      DB::insert(DB::raw($q));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('photo_attribute_types', function(Blueprint $table)
		{
      // Updating photo_attribute_types
      $q = "update photo_attribute_types set attribute_type='project_author' where attribute_type='authors'";
      DB::insert(DB::raw($q));
		});
	}

}
