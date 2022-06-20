<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeGroup extends Model
{
	protected $fillable = ['name', 'status', 'created_at', 'updated_at'];

	public function attributes(): BelongsToMany
	{
		return $this->belongsToMany('App\Models\Attribute', 'attribute_group_has_attributes', 'attribute_group_id', 'attribute_id');
	}
}
