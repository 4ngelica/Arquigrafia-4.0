<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suggestions', function (Blueprint $table) {
            $table->foreign(['attribute_type'])->references(['id'])->on('photo_attribute_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['photo_id'])->references(['id'])->on('photos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suggestions', function (Blueprint $table) {
            $table->dropForeign('suggestions_attribute_type_foreign');
            $table->dropForeign('suggestions_photo_id_foreign');
            $table->dropForeign('suggestions_user_id_foreign');
        });
    }
}
