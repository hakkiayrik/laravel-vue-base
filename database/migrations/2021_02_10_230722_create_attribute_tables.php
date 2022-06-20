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
			$table->string('html_name');
			$table->string('type');
			$table->bigInteger('display_order')->default(0);
			$table->string('options')->nullable();

			$table->unique(['html_name']);
		});

		Schema::create($tableNames["attribute_groups"], function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->tinyInteger('status')->default(1);
			$table->timestamps();

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

		Schema::create($tableNames['model_has_attribute_groups'], function (Blueprint $table) use ($tableNames, $columnNames) {
			$table->unsignedBigInteger('attribute_group_id');
			$table->string('model_type');
			$table->unsignedBigInteger('model_id');

			$table->foreign('attribute_group_id')
				->references('id')
				->on($tableNames['attribute_groups'])
				->onDelete('cascade');


			$table->primary(['attribute_group_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_groups_attribute_group_model_type_primary');
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

		Schema::create('attribute_group_has_attributes', function (Blueprint $table) use ($tableNames) {
			$table->unsignedBigInteger('attribute_id');
			$table->unsignedBigInteger('attribute_group_id');

			$table->foreign('attribute_id')
				->references('id')
				->on($tableNames['attributes'])
				->onDelete('cascade');

			$table->foreign('attribute_group_id')
				->references('id')
				->on($tableNames['attribute_groups'])
				->onDelete('cascade');

			$table->primary(['attribute_id', 'attribute_group_id'], 'group_has_attributes_attribute_id_attribute_groups_id_primary');
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

        Schema::dropIfExists($tableNames["attribute_group_has_attributes"]);
        Schema::dropIfExists($tableNames["model_has_attribute_group"]);
        Schema::dropIfExists($tableNames["model_has_permissions"]);
        Schema::dropIfExists($tableNames["attribute_groups"]);
        Schema::dropIfExists($tableNames["attributes"]);
        Schema::dropIfExists($tableNames["attribute_values"]);
    }
}
