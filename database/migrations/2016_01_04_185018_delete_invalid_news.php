<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteInvalidNews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q  = "delete from news where news_type = 'liked_photo' or news_type = 'new_photo' or news_type = 'edited_photo' or news_type = 'highlight_of_the_week' or news_type = 'evaluated_photo' or news_type = 'new_institutional_photo' and not exists (select photos.* from photos where news.object_id = photos.id)";
		DB::insert(DB::raw($q));

		$q = "delete from news where news_type = 'commented_photo' and not exists (select photos.* from comments, photos where comments.photo_id = photos.id and comments.id = news.object_id)";
		DB::insert(DB::raw($q));

		$q  = "delete from notifications where type = 'photo_liked' and not exists (select photos.* from photos where notifications.object_id = photos.id)";
		DB::insert(DB::raw($q));

		$q = "delete from notifications where type = 'comment_posted' or type = 'comment_liked' and not exists (select photos.* from comments, photos where comments.photo_id = photos.id and comments.id = notifications.object_id)";
		DB::insert(DB::raw($q));

		$q  = "delete from notification_user where not exists (select notifications.* from notifications where notification_user.notification_id = notifications.id)";
		DB::insert(DB::raw($q));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
