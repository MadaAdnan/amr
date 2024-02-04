<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColsPricingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            $table->dropColumn('minimum_price');
            $table->dropColumn('price_per_mile_hour');
            $table->dropColumn('extra_price_per_hour');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            $table->decimal('minimum_price',16,2)->nullable();
            $table->decimal('price_per_mile_hour',16,2)->nullable();
            $table->decimal('extra_price_per_hour',16,2)->nullable();
        });
    }
}
