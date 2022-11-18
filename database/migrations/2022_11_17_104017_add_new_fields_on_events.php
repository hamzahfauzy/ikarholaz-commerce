<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsOnEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            //
            $table->string('status')->nullable();
            $table->string('status_update_by')->nullable();
            $table->string('created_from')->default('gerai');
            $table->string('created_by')->nullable();
            $table->string('created_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            //
            $table->$table->dropColumn('status');
            $table->$table->dropColumn('status_update_by');
            $table->$table->dropColumn('created_from');
            $table->$table->dropColumn('created_by');
            $table->$table->dropColumn('created_user_id');
        });
    }
}
