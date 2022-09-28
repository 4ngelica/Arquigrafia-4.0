<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_elements', function (Blueprint $table) {
            $table->unsignedBigInteger('album_id')->index('album_elements_album_id_foreign');
            $table->unsignedBigInteger('photo_id')->index('album_elements_photo_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('album_elements');
    }
}
