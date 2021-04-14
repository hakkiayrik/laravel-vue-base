<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $fillable = [
    	'image_path'
	];

	public $timestamps = false;
}
