<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_phones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->string('phone');
            $table->string('otp_code');
            $table->timestamp('end_time');
            $table->smallInteger('number_of_attempts')->default(0);
            $table->timestamp('last_attempts')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();
            $table->foreign('device_id')->references('id')->on('otp_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp_phones');
    }
}
