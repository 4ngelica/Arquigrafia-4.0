<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendshipInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friendship_institution', function (Blueprint $table) {
            $table->unsignedBigInteger('following_user_id')->index('friendship_institution_following_user_id_foreign');
            $table->unsignedBigInteger('institution_id')->index('friendship_institution_institution_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friendship_institution');
    }
}
