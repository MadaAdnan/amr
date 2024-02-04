<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Post;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('image')->nullable(true);
            $table->enum('status', [
                Post::PUBLISH,
                Post::DRAFT,
                Post::PENDING,
                Post::REJECTED,
                Post::IN_PROGRESS,
                Post::ADMIN_REJECT,
            ])->default(Post::DRAFT);
            $table->dateTime('date');
            $table->string('author')->nullable(true);
            $table->boolean('show_author')->default(1);

            $table->string('seo_title')->nullable(true);
            $table->string('seo_description')->nullable(true);
            $table->string('seo_keyphrase')->nullable(true);
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('posts');
    }
}
