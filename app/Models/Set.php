<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Set extends Model
{
	protected $fillable = [
		'name'
	];

	public function attributes(): BelongsToMany
	{
		return $this->belongsToMany('App\Models\Attribute', 'set_has_attributes', 'set_id', 'attribute_id');
	}
}
