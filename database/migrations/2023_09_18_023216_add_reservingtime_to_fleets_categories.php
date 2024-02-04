<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReservingtimeToFleetsCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_categories', function (Blueprint $table) {
            $table->bigInteger('split_hour_mechanism')->default(60);
            $table->decimal('split_hour_mechanism_price', 8, 2)->default(1.6);
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
            $table->dropColumn('split_hour_mechanism');
            $table->dropColumn('split_hour_mechanism_price');
        });
    }
}
