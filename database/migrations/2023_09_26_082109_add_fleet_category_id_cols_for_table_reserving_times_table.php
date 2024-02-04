<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFleetCategoryIdColsForTableReservingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserving_times', function (Blueprint $table) {
            $table->bigInteger('fleet_category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reserving_times', function (Blueprint $table) {
            $table->dropColumn('fleet_category_id');
        });
    }
}
