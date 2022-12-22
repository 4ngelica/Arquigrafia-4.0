<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->dateTime('creationDate')->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('albums_user_id_foreign');
            $table->unsignedBigInteger('cover_id')->nullable()->index('album_photo_cover_id_foreign');
            $table->unsignedBigInteger('institution_id')->nullable()->index('albums_institution_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
}
