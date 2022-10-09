<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('accessToken')->nullable();
            $table->integer('accountType')->nullable();
            $table->string('tokenSecret')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('external_accounts_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_accounts');
    }
}
