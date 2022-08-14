<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateModerationTablesWithNewTypes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Updating project_author
		$q = "update photo_attribute_types set attribute_type='project_author' where attribute_type='workAuthor'";
		DB::insert(DB::raw($q));

		// Deleting old moderation_types
		$q = "delete from moderation_types where moderation_type='junior'";
		DB::insert(DB::raw($q));
		$q = "delete from moderation_types where moderation_type='pleno'";
		DB::insert(DB::raw($q));
		$q = "delete from moderation_types where moderation_type='senior'";
		DB::insert(DB::raw($q));

		// Inserting new moderation_types
		$q = "insert into moderation_types (moderation_type) values ('reviewer')";
		DB::insert(DB::raw($q));
		$q = "insert into moderation_types (moderation_type) values ('editor')";
		DB::insert(DB::raw($q));
		$q = "insert into moderation_types (moderation_type) values ('moderator')";
		DB::insert(DB::raw($q));
		$q = "insert into moderation_types (moderation_type) values ('curator')";
		DB::insert(DB::raw($q));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Deleting moderation_types
		$q = "delete from moderation_types where moderation_type='reviewer'";
		DB::insert(DB::raw($q));
		$q = "delete from moderation_types where moderation_type='editor'";
		DB::insert(DB::raw($q));
		$q = "delete from moderation_types where moderation_type='moderator'";
		DB::insert(DB::raw($q));
		$q = "delete from moderation_types where moderation_type='curator'";
		DB::insert(DB::raw($q));

		// Reinserting old moderation types
		$q = "insert into moderation_types (moderation_type) values ('junior')";
		DB::insert(DB::raw($q));
		$q = "insert into moderation_types (moderation_type) values ('pleno')";
		DB::insert(DB::raw($q));
		$q = "insert into moderation_types (moderation_type) values ('senior')";
		DB::insert(DB::raw($q));


		// Updating photo_attribute_types
		$q = "update photo_attribute_types set attribute_type='workAuthor' where attribute_type='project_author'";
		DB::insert(DB::raw($q));
	}

}
