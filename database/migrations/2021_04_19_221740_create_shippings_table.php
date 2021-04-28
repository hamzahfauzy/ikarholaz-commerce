<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_item_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('fullname');
            $table->string('province_id');
            $table->string('district_id');
            $table->string('subdistrict_id')->nullable();
            $table->string('address');
            $table->string('postal_code');
            $table->string('courir_name');
            $table->integer('courir_id');
            $table->string('service_name');
            $table->integer('service_id');
            $table->float('service_rates',10,2);
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
        Schema::dropIfExists('shippings');
    }
}
