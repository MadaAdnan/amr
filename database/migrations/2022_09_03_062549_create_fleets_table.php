<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Fleet;

class CreateFleetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('image')->nullable(true);

            $table->integer('seats');
            $table->integer('luggage');
            $table->integer('passengers');
            
            $table->enum('status', [
                Fleet::PUBLISH,
                Fleet::DRAFT,
            ])->default(Fleet::DRAFT);

            $table->string('seo_title')->nullable(true);
            $table->string('seo_description')->nullable(true);
            $table->string('seo_keyphrase')->nullable(true);
            $table->bigInteger('user_id');
            $table->bigInteger('category_id');
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
        Schema::dropIfExists('fleets');
    }
}
