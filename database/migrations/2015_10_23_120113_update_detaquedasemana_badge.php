<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDetaquedasemanaBadge extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// $badge = modules\gamification\models\Badge::whereName('Destaque da Semana')->first();
		// if ( ! is_null($badge) ) {
		// 	$badge->image = 'destaque_da_semana.png';
		// 	$badge->save();
		// }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// $badge = modules\gamification\models\Badge::whereName('Destaque da Semana')->first();
		// if ( ! is_null($badge) ) {
		// 	$badge->image = null;
		// }
	}

}
