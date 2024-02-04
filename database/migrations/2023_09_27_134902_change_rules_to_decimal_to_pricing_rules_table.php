<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//use DB;
class ChangeRulesToDecimalToPricingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        Schema::table('price_rules', function (Blueprint $table) {
            $table->decimal('extra_price_per_mile',16,2)->nullable()->change();
            $table->decimal('price_per_hour',16,2)->nullable()->change();
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
            $table->dropColumn('extra_price_per_mile');
            $table->dropColumn('price_per_hour');
        });
    }
}
