<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillAcervoAuthorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('authors')->insert(    		array(
			array("name" =>"ABREU, Abelardo Gomes de", "approved" => 1),
			array("name" =>"FIALHO, Leonardo Stucker", "approved" => 1),
			array("name" =>"FILIPPO, Juan Carlos Di", "approved" => 1),
		));
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
