<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinomialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('binomials', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('defaultValue');
			// $table->string('firstDescription')->nullable(); não são necessários
			// $table->string('firstLink')->nullable(); não são necessários
			//$table->string('firstName')->nullable(); renomeado para firstOption
			$table->string('firstOption')->nullable();
			// $table->string('secondDescription')->nullable(); não são necessários
			// $table->string('secondLink')->nullable(); não são necessários
			// $table->string('secondName')->nullable(); renomeado para secondOption
			$table->string('secondOption')->nullable();
      
      /*
      valores para o arquigrafia - SQL
      
      INSERT INTO `arquigrafia`.`binomial`
        (`id`, `defaultValue`, `firstOption`, `secondOption`)
      VALUES
        (13,50,'Horizontal','Vertical'),
        (14,50,'Translúcida','Opaca'),
        (16,50,'Simétrica','Assimétrica'),
        (19,50,'Complexa','Simples'),
        (20,50,'Interna','Externa'),
        (21,50,'Aberta','Fechada');
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
		Schema::drop('binomials');
	}

}
