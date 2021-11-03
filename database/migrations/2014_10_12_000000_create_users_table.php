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
            $table->string('username')->unique();
            $table->tinyInteger('role')->default(2);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('confirmed')->default(0);
            $table->integer('points')->default(0);
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('referrer_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->dateTime('last_visit')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
