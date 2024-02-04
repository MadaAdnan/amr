<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyColsToFleetCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_categories', function (Blueprint $table) {
            $table->time('daily_from')->nullable();
            $table->time('daily_to')->nullable();
            $table->decimal('daily_price',16,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet_categories', function (Blueprint $table) {
            $table->dropColumn('daily_from');
            $table->dropColumn('daily_to');
            $table->dropColumn('daily_price');
        });
    }
}
