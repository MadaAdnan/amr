<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableReservationsChangePrise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->decimal('price',16,2)->change();
            $table->decimal('tip',16,2)->nullable()->change();
            $table->decimal('price_with_tip',16,2)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->bigInteger('price');
            $table->bigInteger('tip')->nullable();
            $table->bigInteger('price_with_tip');


        });
    }
}
