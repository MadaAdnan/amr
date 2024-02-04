<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReservingAndDailyColsToCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->time('daily_from')->nullable();
            $table->time('daily_to')->nullable();
            $table->bigInteger('daily_price')->nullable();
            $table->bigInteger('split_hour_mechanism')->nullable();
            $table->bigInteger('split_hour_mechanism_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('daily_from');
            $table->dropColumn('daily_to');
            $table->dropColumn('daily_price');
            $table->dropColumn('split_hour_mechanism');
            $table->dropColumn('split_hour_mechanism_price');
        });
    }
}
