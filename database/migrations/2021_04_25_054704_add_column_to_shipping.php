<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToShipping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippings', function (Blueprint $table) {
            //
            $table->string('province_name');
            $table->string('district_name');
            $table->string('subdistrict_name')->nullable();
            $table->string('resi_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shippings', function (Blueprint $table) {
            //
            $table->dropColumn(['province_name','district_name','subdistrict_name','resi_number']);
        });
    }
}
