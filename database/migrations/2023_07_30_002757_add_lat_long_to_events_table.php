<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatLongToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('events', function (Blueprint $table) {
        //     $table->decimal('latitude', 10, 8);
        //     $table->decimal('longitude', 10, 8);
        //     $table->json('radius');
        //     $table->bigInteger('price');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('radius');
            $table->dropColumn('price');
        });
    }
}
