<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_applications', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("state");
            $table->string("city");
            $table->string("address");
            $table->string("zip_code");
            $table->string("phone");
            $table->string("email");
            $table->string("website");

            $table->string("contact_person");
            $table->string("contact_phone");
            $table->string("contact_email");

            $table->string("fleet_size");
            $table->string("area_of_service");
            $table->string("airports");
            $table->string("tax_id");
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
        Schema::dropIfExists('affiliate_applications');
    }
}
