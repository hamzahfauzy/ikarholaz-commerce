<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInAlumni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            //
            $table->integer('year_in')->nullable();
            $table->integer('year_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            //
            $table->dropColumns(['year_in','year_out']);
        });
    }
}
