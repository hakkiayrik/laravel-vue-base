<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
			$table->bigIncrements('id');
			$table->unsignedInteger('admin_id');
			$table->dateTime("published_date");
			$table->enum("type", ["article", "video"]);
			$table->bigInteger("like")->default(0);
			$table->bigInteger("dislike")->default(0);
			$table->enum("visibility", ["public", "private"]);
			$table->string("video_url")->nullable();
			$table->tinyInteger("status")->default(0);
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
