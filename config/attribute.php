<?php

return [

	'models' => [

		'set' => App\Models\Set::class,
		'attribute' => App\Models\Attribute::class,
		'attribute_value' => App\Models\AttributeValue::class
	],

	'table_names' => [

		'sets' => 'sets',
		'attributes' => 'attributes',
		'attribute_values' => 'attribute_values',
		'model_has_sets' => 'model_has_sets',
		'model_has_attributes' => 'model_has_attributes',
		'model_has_values' => 'model_has_values',
		'set_has_attributes' => 'set_has_attributes',
	],

	'column_names' => [
		'model_morph_key' => 'model_id',
	]
];
