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
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->string('name');
            $table->string('graduation_year');
            $table->string('class_name')->nullable();
            $table->string('NRA')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('latitute')->nullable();
            $table->string('longitude')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->text('biography')->nullable();
            $table->string('profile_pic')->nullable();
            $table->date('registration_date')->nullable();
            $table->string('approval_status')->nullable();
            $table->string('approval_by')->nullable();
            $table->boolean('private_email')->default(0)->nullable();
            $table->boolean('private_phone')->default(0)->nullable();
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
