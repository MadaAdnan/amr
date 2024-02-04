<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFleetCategoriesTabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_categories', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->text('category_description')->nullable();
            $table->smallInteger('passengers')->nullable();
            $table->smallInteger('luggage')->nullable();
            $table->smallInteger('flight_tracking')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fleet_categories');
    }
}
