<?php

return [

	'models' => [

		'attribute_group' => App\Models\AttributeGroup::class,
		'attribute' => App\Models\Attribute::class,
		'attribute_value' => App\Models\AttributeValue::class
	],

	'table_names' => [

		'attribute_groups' => 'attribute_groups',
		'attributes' => 'attributes',
		'attribute_values' => 'attribute_values',
		'model_has_attribute_groups' => 'model_has_attribute_groups',
		'model_has_attributes' => 'model_has_attributes',
		'model_has_values' => 'model_has_values',
		'attribute_group_has_attributes' => 'attribute_group_has_attributes',
	],

	'column_names' => [
		'model_morph_key' => 'model_id',
	]
];
