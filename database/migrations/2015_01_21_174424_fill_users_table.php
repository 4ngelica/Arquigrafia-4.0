<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
      /*
      User::create(['name'=>'Pedro Emilio Guglielmo', 'email'=>'pedrinho.e@gmail.com', 'password'=>Hash::make('123456'), 'login'=>'pedro', 'country'=>'Brasil', 'state'=>'São Paulo', 'city'=>'São Paulo', 'address'=>'Rua Ponta Porã,700', 'birthday'=>'10/06/88', 'company'=>'Rocket Science', 'profession'=>'web design', 'relationship'=>'solteiro', 'sholarity'=>'Ensino Superior', 'language'=>'português', 'photo'=>'/profile/10/showphotoprofile/a2f7570f-b235-4622-8402-7f6cf00013c0/']);
      User::create(['name'=>'João Marcopito', 'email'=>'joaom@gmail.com', 'password'=>Hash::make('123456'), 'login'=>'joao', 'country'=>'Brasil', 'state'=>'São Paulo', 'city'=>'São Paulo', 'address'=>'Rua Pio XI,50', 'birthday'=>'15/10/90', 'company'=>'Arquigrafia', 'profession'=>'arquiteto', 'relationship'=>'solteiro', 'sholarity'=>'Ensino Superior', 'language'=>'português', 'photo'=>'/profile/10/showphotoprofile/bb9c9f2f-6527-4cd3-a91d-d96115ecf5b0/']);
      User::create(['name'=>'Gustavo Vallo', 'email'=>'gustavo@hotmail.com', 'password'=>Hash::make('123456'), 'login'=>'gustavo', 'country'=>'Brasil', 'state'=>'São Paulo', 'city'=>'São Paulo', 'address'=>'Rua Ponta Porã,700', 'birthday'=>'10/06/88', 'company'=>'Photo Way', 'profession'=>'fotógrafo', 'relationship'=>'solteiro', 'sholarity'=>'Ensino Superior', 'language'=>'português', 'photo'=>'/profile/10/showphotoprofile/9b490c7b-d3b3-47ff-ba38-88dc6ab49361/']);
      */
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			//
		});
	}

}
