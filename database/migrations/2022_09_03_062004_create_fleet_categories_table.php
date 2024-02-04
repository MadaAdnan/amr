<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_categories', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->boolean('status')->default(1);
            $table->string('seo_title')->nullable(true);
            $table->string('seo_description')->nullable(true);
            $table->string('seo_keyphrase')->nullable(true);
            
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
        Schema::dropIfExists('fleet_categories');
    }
}
