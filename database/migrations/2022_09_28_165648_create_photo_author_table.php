<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoAuthorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_author', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('photo_id')->index('photo_author_photo_id_foreign');
            $table->unsignedBigInteger('author_id')->index('photo_author_author_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photo_author');
    }
}
