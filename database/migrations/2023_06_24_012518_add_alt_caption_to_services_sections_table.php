<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAltCaptionToServicesSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services_sections', function (Blueprint $table) {
            $table->text('alt')->nullable();
            $table->text('caption')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services_sections', function (Blueprint $table) {
            $table->dropColumn('alt');
            $table->dropColumn('caption');
        });
    }
}
