<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$tableNames = config('attribute.table_names');
		$columnNames = config('attribute.column_names');

		if (empty($tableNames)) {
			throw new \Exception('Error: config/attribute.php not loaded. Run [php artisan config:clear] and try again.');
		}

		Schema::create($tableNames["attributes"], function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('label');
			$table->string('name');
			$table->string('type');
			$table->string('option')->nullable();

			$table->unique(['name']);
		});

		Schema::create($tableNames["sets"], function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');

			$table->unique(['name']);
		});

		Schema::create($tableNames["attribute_values"], function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('value');
		});

		Schema::create($tableNames['model_has_attributes'], function (Blueprint $table) use ($tableNames, $columnNames) {
			$table->unsignedBigInteger('attribute_id')->unsigned();
			$table->string('model_type');
			$table->unsignedBigInteger('model_id');

			$table->foreign('attribute_id')
				->references('id')
				->on($tableNames['attributes'])
				->onDelete('cascade');

			$table->primary(['attribute_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_attributes_attribute_model_type_primary');
		});

		Schema::create($tableNames['model_has_sets'], function (Blueprint $table) use ($tableNames, $columnNames) {
			$table->unsignedBigInteger('set_id');
			$table->string('model_type');
			$table->unsignedBigInteger('model_id');

			$table->foreign('set_id')
				->references('id')
				->on($tableNames['sets'])
				->onDelete('cascade');


			$table->primary(['set_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_sets_set_model_type_primary');
		});

		Schema::create($tableNames['model_has_values'], function (Blueprint $table) use ($tableNames, $columnNames) {
			$table->unsignedBigInteger('attribute_id');
			$table->unsignedBigInteger('value_id');
			$table->string('model_type');
			$table->unsignedBigInteger('model_id');

			$table->foreign('attribute_id')
				->references('id')
				->on($tableNames['attributes'])
				->onDelete('cascade');

			$table->foreign('value_id')
				->references('id')
				->on($tableNames['attribute_values'])
				->onDelete('cascade');

			$table->primary(['attribute_id', 'value_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_attributes_attribute_model_type_primary');
		});

		Schema::create('set_has_attributes', function (Blueprint $table) use ($tableNames) {
			$table->unsignedBigInteger('attribute_id');
			$table->unsignedBigInteger('set_id');

			$table->foreign('attribute_id')
				->references('id')
				->on($tableNames['attributes'])
				->onDelete('cascade');

			$table->foreign('set_id')
				->references('id')
				->on($tableNames['sets'])
				->onDelete('cascade');

			$table->primary(['attribute_id', 'set_id'], 'set_has_attributes_attribute_id_set_id_primary');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$tableNames = config('attribute.table_names');

		if (empty($tableNames)) {
			throw new \Exception('Error: config/attribute.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
		}

        Schema::dropIfExists($tableNames["set_has_attributes"]);
        Schema::dropIfExists($tableNames["model_has_sets"]);
        Schema::dropIfExists($tableNames["model_has_permissions"]);
        Schema::dropIfExists($tableNames["sets"]);
        Schema::dropIfExists($tableNames["attributes"]);
        Schema::dropIfExists($tableNames["attribute_values"]);
    }
}
