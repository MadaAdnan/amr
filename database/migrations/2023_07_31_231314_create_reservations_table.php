<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('pick_up_location');
            $table->string('drop_off_location')->nullable();
            $table->date('pick_up_date');
            $table->date('return_date')->nullable();
            $table->time('pick_up_time');
            $table->time('return_time')->nullable();
            $table->bigInteger('tip')->nullable();
            $table->bigInteger('price');
            $table->bigInteger('duration')->nullable();
            $table->bigInteger('distance')->nullable();
            $table->string('phone_primary');
            $table->string('phone_secondary')->nullable();
            $table->string('flight_number')->nullable();
            $table->text('comment')->nullable();
            $table->string('airline')->nullable();
            $table->bigInteger('service_type');
            $table->bigInteger('price_with_tip');
            $table->bigInteger('user_id');
            $table->bigInteger('driver_id')->nullable();
            $table->bigInteger('coupon_id')->nullable();
            $table->bigInteger('category_id');
            $table->bigInteger('company_id')->nullable();
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
        Schema::dropIfExists('reservations');
    }
}
