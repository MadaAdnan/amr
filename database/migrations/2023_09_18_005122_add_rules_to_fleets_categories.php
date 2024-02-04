<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRulesToFleetsCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_categories', function (Blueprint $table){
            $table->json('pricing_rules')->nullable();
        });

        DB::table('fleet_categories')->update(['pricing_rules' => json_encode([
            'initial_fee' => 20,
            'minimum_hour' => 1,
            'minimum_mile_hour' => 5,
            'minimum_mile' => 50,
            'price_per_hour' => 50,
            'extra_price_per_mile' => 1.6,
        ])]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet_categories', function (Blueprint $table) {
            $table->dropColumn('pricing_rules');
        });
    }
}
