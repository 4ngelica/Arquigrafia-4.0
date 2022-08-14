<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefillBadgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::update('update photos set badge_id = null');
		DB::update('update comments set badge_id = null');
		DB::delete('delete from user_badges');
		DB::delete('delete from badges');
		// Badge::create(['name'=>'Iniciante', 'description'=>'usuário realizou 2 avaliações', 'class'=>'Bronze']);
		// Badge::create(['name'=>'Veterano', 'description'=>'usuário realizou 5 avaliações', 'class'=>'Bronze']);
		// Badge::create(['name'=>'Arquiteto', 'description'=>'usuário realizou 10 avaliações', 'class'=>'Bronze']);
		// Badge::create(['name'=>'Especialista', 'description'=>'usuário realizou 20 avaliações', 'class'=>'Silver']);
		// Badge::create(['name'=>'Professor', 'description'=>'usuário realizou 50 avaliações', 'class'=>'Silver']);
		// Badge::create(['name'=>'Master', 'description'=>'usuário realizou 100 avaliações', 'class'=>'Gold']);
		// Badge::create(['name'=>'Rei', 'description'=>'usuário realizou 100+ avaliações', 'class'=>'Gold']);	
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
