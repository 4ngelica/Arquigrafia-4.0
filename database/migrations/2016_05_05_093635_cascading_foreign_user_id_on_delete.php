<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CascadingForeignUserIdOnDelete extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$q = "ALTER TABLE `albums` DROP FOREIGN KEY `albums_user_id_foreign`; ALTER TABLE `albums` ADD CONSTRAINT `albums_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);

		$q = "ALTER TABLE `binomial_evaluation` DROP FOREIGN KEY `binomial_evaluation_user_id_foreign`; ALTER TABLE `binomial_evaluation` ADD CONSTRAINT `binomial_evaluation_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);

		$q = "ALTER TABLE `comments` DROP FOREIGN KEY `comments_user_id_foreign`; ALTER TABLE `comments` ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);

		$q = "ALTER TABLE `employees` DROP FOREIGN KEY `employees_user_id_foreign`; ALTER TABLE `employees` ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);

		$q = "ALTER TABLE `friendship` DROP FOREIGN KEY `friendship_following_id_foreign`; ALTER TABLE `friendship` ADD CONSTRAINT `friendship_following_id_foreign` FOREIGN KEY (`following_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);

		$q = "ALTER TABLE `friendship` DROP FOREIGN KEY `friendship_followed_id_foreign`; ALTER TABLE `friendship` ADD CONSTRAINT `friendship_followed_id_foreign` FOREIGN KEY (`followed_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);	

		$q = "ALTER TABLE `friendship_institution` DROP FOREIGN KEY `friendship_institution_following_user_id_foreign`; ALTER TABLE `friendship_institution` ADD CONSTRAINT `friendship_institution_following_user_id_foreign` FOREIGN KEY (`following_user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);

		$q = "ALTER TABLE `likes` DROP FOREIGN KEY `likes_user_id_foreign`; ALTER TABLE `likes` ADD CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);	

		$q = "ALTER TABLE `medals` DROP FOREIGN KEY `medals_user_id_foreign`; ALTER TABLE `medals` ADD CONSTRAINT `medals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);	

		$q = "ALTER TABLE `occupations` DROP FOREIGN KEY `occupations_user_id_foreign`; ALTER TABLE `occupations` ADD CONSTRAINT `occupations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);	

		$q = "ALTER TABLE `photos` DROP FOREIGN KEY `photos_user_id_foreign`; ALTER TABLE `photos` ADD CONSTRAINT `photos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);

		$q = "ALTER TABLE `users_roles` DROP FOREIGN KEY `users_roles_user_id_foreign`; ALTER TABLE `users_roles` ADD CONSTRAINT `users_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);	

		$q = "ALTER TABLE `user_badges` DROP FOREIGN KEY `user_badges_user_id_foreign`; ALTER TABLE `user_badges` ADD CONSTRAINT `user_badges_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `arquigrafia`.`users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);	

		$q = "ALTER TABLE `album_elements` DROP FOREIGN KEY `album_elements_album_id_foreign`; ALTER TABLE `album_elements` ADD CONSTRAINT `album_elements_album_id_foreign` FOREIGN KEY (`album_id`) REFERENCES `arquigrafia`.`albums`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);

		$q = "ALTER TABLE `tag_assignments` DROP FOREIGN KEY `tag_assignments_tag_id_foreign`; ALTER TABLE `tag_assignments` ADD CONSTRAINT `tag_assignments_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `arquigrafia`.`tags`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);		

		$q = "ALTER TABLE `tag_assignments` DROP FOREIGN KEY `tag_assignments_photo_id_foreign`; ALTER TABLE `tag_assignments` ADD CONSTRAINT `tag_assignments_photo_id_foreign` FOREIGN KEY (`photo_id`) REFERENCES `arquigrafia`.`photos`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;";		
		DB::unprepared($q);	
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
