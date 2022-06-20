<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorizableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorizable', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->morphs('categorizable');
            $table->timestamps();
        });

		Schema::table('categorizable', function ($table) {
			$table->unique(['category_id', 'categorizable_id', 'categorizable_type'], 'categorizable_ids_type_unique');
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorizables');
    }
}
