<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_translations', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('category_id');
			$table->string('locale')->index();
			$table->string("name");
            $table->string('slug')->unique();
            $table->text("description")->nullable();
        });

		Schema::table('category_translations', function($table) {
			$table->unique(['category_id', 'locale']);
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_translations');
    }
}
