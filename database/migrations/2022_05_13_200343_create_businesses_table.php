<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->string("sektor")->nullable();
            $table->string("badan_hukum")->nullable();
            $table->string("kepemilikan")->nullable();
            $table->string("status_kepemilikan")->nullable();
            $table->string("skala")->nullable();
            $table->string("berdiri_sejak")->nullable();
            $table->string("pencapaian")->nullable();
            $table->string("alamat")->nullable();
            $table->string("no_telepon")->nullable();
            $table->string("email")->nullable();
            $table->string("website")->nullable();
            $table->string("jumlah_sdm")->nullable();
            $table->string("ijin_usaha")->nullable();
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
        Schema::dropIfExists('businesses');
    }
}
