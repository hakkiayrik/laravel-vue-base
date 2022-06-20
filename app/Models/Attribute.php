<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
	protected $fillable = [
		'label', 'html_name', 'type', 'options', 'display_order'
	];

    public $timestamps = false;
}
