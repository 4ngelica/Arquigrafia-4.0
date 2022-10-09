<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('aditionalImageComments')->nullable();
            $table->string('allowCommercialUses')->nullable();
            $table->string('allowModifications')->nullable();
            $table->string('cataloguingTime')->nullable();
            $table->string('characterization')->nullable();
            $table->string('city')->nullable();
            $table->string('collection')->nullable();
            $table->string('country')->nullable();
            $table->string('dataCriacao')->nullable();
            $table->dateTime('dataUpload')->nullable();
            $table->boolean('deleted')->default(false);
            $table->string('description', 1000)->nullable();
            $table->string('district')->nullable();
            $table->string('imageAuthor')->nullable();
            $table->string('name');
            $table->string('nome_arquivo');
            $table->string('state')->nullable();
            $table->string('street')->nullable();
            $table->string('tombo')->nullable();
            $table->string('workdate')->nullable();
            $table->unsignedBigInteger('user_id')->index('photos_user_id_foreign');
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
            $table->softDeletes();
            $table->string('support')->nullable();
            $table->string('subject')->nullable();
            $table->dateTime('hygieneDate')->nullable();
            $table->dateTime('backupDate')->nullable();
            $table->string('UserResponsible')->nullable();
            $table->string('observation')->nullable();
            $table->unsignedBigInteger('institution_id')->nullable()->index('photos_institution_id_foreign');
            $table->boolean('authorized')->default(true);
            $table->string('workDateType')->nullable();
            $table->string('imageDateType')->nullable();
            $table->timestamp('draft')->nullable();
            $table->string('video')->nullable();
            $table->string('type')->nullable();
            $table->boolean('accepted')->default(false);
            $table->string('project_author');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
