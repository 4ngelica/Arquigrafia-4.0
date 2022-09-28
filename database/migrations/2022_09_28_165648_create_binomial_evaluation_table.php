<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinomialEvaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binomial_evaluation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('photo_id')->index('binomial_evaluation_photo_id_foreign');
            $table->integer('evaluationPosition');
            $table->unsignedBigInteger('binomial_id')->nullable()->index('binomial_evaluation_binomial_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('binomial_evaluation_user_id_foreign');
            $table->enum('knownArchitecture', ['yes', 'no'])->nullable()->default('no');
            $table->enum('areArchitecture', ['yes', 'no'])->nullable()->default('no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('binomial_evaluation');
    }
}
