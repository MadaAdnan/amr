<?php

use App\Models\Reservation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReservationStatusEnumValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status', [
                Reservation::PENDING,
                Reservation::ACCEPTED,
                Reservation::ASSIGNED,
                Reservation::ON_THE_WAY,
                Reservation::ARRIVED_AT_THE_PICKUP_LOCATION,
                Reservation::PASSENGER_ON_BOARD,
                Reservation::COMPLETED,
                Reservation::CANCELED,
                Reservation::LATE_CANCELED,
                Reservation::NO_SHOW,
                Reservation::Draft,
                Reservation::FAILED,
            ])->default(Reservation::PENDING);
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
          $table->dropColumn('status');
        });
    }
}
