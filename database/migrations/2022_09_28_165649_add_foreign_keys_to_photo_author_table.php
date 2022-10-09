<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPhotoAuthorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photo_author', function (Blueprint $table) {
            $table->foreign(['author_id'])->references(['id'])->on('authors')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['photo_id'])->references(['id'])->on('photos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photo_author', function (Blueprint $table) {
            $table->dropForeign('photo_author_author_id_foreign');
            $table->dropForeign('photo_author_photo_id_foreign');
        });
    }
}
