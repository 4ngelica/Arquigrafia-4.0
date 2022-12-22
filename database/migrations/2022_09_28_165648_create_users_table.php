<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastName')->nullable();
            $table->string('login')->unique();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('email');
            $table->string('password');
            $table->string('oldPassword')->nullable();
            $table->boolean('oldAccount')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('birthday')->nullable();
            $table->string('scholarity')->nullable();
            $table->string('language')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('site')->nullable();
            $table->string('id_facebook')->nullable();
            $table->string('id_instagram')->nullable();
            $table->string('id_twitter')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
            $table->string('id_stoa')->nullable();
            $table->enum('visibleBirthday', ['yes', 'no'])->nullable()->default('no');
            $table->enum('visibleEmail', ['yes', 'no'])->nullable()->default('no');
            $table->integer('invitations')->default(0);
            $table->string('verify_code')->nullable();
            $table->enum('active', ['yes', 'no'])->nullable()->default('no');
            $table->integer('nb_eval')->default(0);
            $table->string('mobile_token', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
