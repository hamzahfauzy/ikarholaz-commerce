<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->string("bidang")->nullable();
            $table->string("cakupan")->nullable();
            $table->string("kantor")->nullable();
            $table->string("berdiri_sejak")->nullable();
            $table->string("pencapaian")->nullable();
            $table->string("keaktifan")->nullable();
            $table->string("no_telepon")->nullable();
            $table->string("email")->nullable();
            $table->string("website")->nullable();
            $table->string("jumlah_anggota")->nullable();
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
        Schema::dropIfExists('communities');
    }
}
