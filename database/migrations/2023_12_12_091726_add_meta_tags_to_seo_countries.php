<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaTagsToSeoCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seo_countries', function (Blueprint $table) {
            $table->text('seo_city_title')->nullable();
            $table->text('seo_city_description')->nullable();
            $table->text('seo_city_key_phrase')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seo_countries', function (Blueprint $table) {
            //
        });
    }
}
