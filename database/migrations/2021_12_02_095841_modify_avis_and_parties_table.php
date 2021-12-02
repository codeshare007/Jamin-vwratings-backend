<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAvisAndPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avis', function(Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('parties', function(Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avis', function(Blueprint $table) {
            $table->dropUnique('name');
        });

        Schema::table('parties', function(Blueprint $table) {
            $table->dropUnique('name');
        });
    }
}
