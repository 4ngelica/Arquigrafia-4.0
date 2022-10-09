<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFriendshipInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('friendship_institution', function (Blueprint $table) {
            $table->foreign(['following_user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['institution_id'])->references(['id'])->on('institutions')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('friendship_institution', function (Blueprint $table) {
            $table->dropForeign('friendship_institution_following_user_id_foreign');
            $table->dropForeign('friendship_institution_institution_id_foreign');
        });
    }
}
