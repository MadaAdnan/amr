<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChauffeurApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chauffeur_applications', function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->string("phone");
            $table->string("email");
            $table->string("state");
            $table->string("city");
            $table->string("address");
            $table->dateTime('date_of_birth');
            $table->string("experience_years");
            $table->boolean("availability");
            $table->boolean("texas_license");
            $table->boolean("houston_limo_license");
            $table->string("resume");
            $table->string("additional_information")->nullable(true);

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
        Schema::dropIfExists('chauffeur_applications');
    }
}