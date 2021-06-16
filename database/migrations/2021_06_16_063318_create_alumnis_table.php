<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('graduation_year');
            $table->string('class_name');
            $table->string('NRA');
            $table->string('email');
            $table->string('gender');
            $table->string('place_of_birth');
            $table->string('date_of_birth');
            $table->text('address');
            $table->string('latitute');
            $table->string('longitude');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->text('biography');
            $table->string('profile_pic');
            $table->date('registration_date');
            $table->string('approval_status');
            $table->string('approval_by');
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
        Schema::dropIfExists('alumnis');
    }
}
