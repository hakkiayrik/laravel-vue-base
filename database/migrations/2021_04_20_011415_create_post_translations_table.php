<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_translations', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('post_id');
			$table->string('locale')->index();
			$table->string("title");
			$table->string("slug")->unique();
			$table->text("content");
			$table->string("meta_keywords");
			$table->string("meta_description");
        });

		Schema::table('post_translations', function($table) {
			$table->unique(['post_id', 'locale']);
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_translations');
    }
}
