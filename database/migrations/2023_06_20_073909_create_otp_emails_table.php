<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp_code');
            $table->timestamp('end_time');
            $table->smallInteger('number_of_attempts')->default(0);
            $table->timestamp('last_attempts')->nullable();
            $table->smallInteger('status')->default(1);
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
        Schema::dropIfExists('otp_emails');
    }
}
