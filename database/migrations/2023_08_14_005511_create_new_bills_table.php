<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number');
            $table->bigInteger('user_id');
            $table->bigInteger('total_amount');
            $table->string('card_brand');
            $table->string('last_four');
            $table->bigInteger('coupon_id')->nullable();
            $table->enum('type',['Trip','Extra'])->default('Trip');
            $table->bigInteger('reservation_id');
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
        Schema::dropIfExists('new_bills');
    }
}
