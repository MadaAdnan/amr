<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserving_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('period');
            $table->decimal('charge',16,2);
            $table->bigInteger('city_id')->nullable();
            $table->integer('from_hour');
            $table->integer('to_hour');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserving_times');
    }
}
