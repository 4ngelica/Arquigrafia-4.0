<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBinomialEvaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('binomial_evaluation', function (Blueprint $table) {
            $table->foreign(['binomial_id'])->references(['id'])->on('binomials')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['photo_id'])->references(['id'])->on('photos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('binomial_evaluation', function (Blueprint $table) {
            $table->dropForeign('binomial_evaluation_binomial_id_foreign');
            $table->dropForeign('binomial_evaluation_photo_id_foreign');
            $table->dropForeign('binomial_evaluation_user_id_foreign');
        });
    }
}
