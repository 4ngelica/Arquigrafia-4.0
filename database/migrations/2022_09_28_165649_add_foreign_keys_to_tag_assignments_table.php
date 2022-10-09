<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTagAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag_assignments', function (Blueprint $table) {
            $table->foreign(['photo_id'])->references(['id'])->on('photos')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['tag_id'])->references(['id'])->on('tags')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tag_assignments', function (Blueprint $table) {
            $table->dropForeign('tag_assignments_photo_id_foreign');
            $table->dropForeign('tag_assignments_tag_id_foreign');
        });
    }
}
