<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropResumeColFromChauffeurApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chauffeur_applications', function (Blueprint $table) {
            $table->dropColumn('resume');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chauffeur_applications', function (Blueprint $table) {
            $table->string('resume')->nullable();
        });
    }
}
