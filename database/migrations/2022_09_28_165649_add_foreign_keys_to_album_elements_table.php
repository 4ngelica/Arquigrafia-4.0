<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAlbumElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('album_elements', function (Blueprint $table) {
            $table->foreign(['album_id'])->references(['id'])->on('albums')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('album_elements', function (Blueprint $table) {
            $table->dropForeign('album_elements_album_id_foreign');
            $table->dropForeign('album_elements_photo_id_foreign');
        });
    }
}
