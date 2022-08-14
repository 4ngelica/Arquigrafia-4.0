<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateModerationTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q = "insert into photo_attribute_types (attribute_type) values ('city')";
		DB::insert(DB::raw($q));
		$q = "insert into photo_attribute_types (attribute_type) values ('country')";
		DB::insert(DB::raw($q));
		$q = "insert into photo_attribute_types (attribute_type) values ('description')";
		DB::insert(DB::raw($q));
		$q = "insert into photo_attribute_types (attribute_type) values ('district')";
		DB::insert(DB::raw($q));
		$q = "insert into photo_attribute_types (attribute_type) values ('imageAuthor')";
		DB::insert(DB::raw($q));
		$q = "insert into photo_attribute_types (attribute_type) values ('state')";
		DB::insert(DB::raw($q));
		$q = "insert into photo_attribute_types (attribute_type) values ('street')";
		DB::insert(DB::raw($q));
		$q = "insert into photo_attribute_types (attribute_type) values ('name')";
		DB::insert(DB::raw($q));
		$q = "insert into photo_attribute_types (attribute_type) values ('workAuthor')";
		DB::insert(DB::raw($q));
		$q = "insert into photo_attribute_types (attribute_type) values ('workDate')";
		DB::insert(DB::raw($q));

		$q = "insert into moderation_types (moderation_type) values ('junior')";
		DB::insert(DB::raw($q));
		$q = "insert into moderation_types (moderation_type) values ('pleno')";
		DB::insert(DB::raw($q));
		$q = "insert into moderation_types (moderation_type) values ('senior')";
		DB::insert(DB::raw($q));

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$q = "delete from photo_attribute_types";
		DB::insert(DB::raw($q));
		$q = "delete from moderation_types";
		DB::insert(DB::raw($q));
	}

}
