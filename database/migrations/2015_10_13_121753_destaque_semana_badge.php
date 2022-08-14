<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\modules\gamification\models\Badge;

class DestaqueSemanaBadge extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Badge::create([
		// 	'name' => 'Destaque da Semana',
		// 	'description' => 'Recebeu pelo menos 5 curtidas em uma única imagem em uma semana',
		// 	'class' => 'Gold'
		// ]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Badge::whereName('Destaque da Semana')->first()->delete();
	}

}
