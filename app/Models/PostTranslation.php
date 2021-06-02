<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    use HasFactory;

	public $timestamps = false;
	protected $fillable = ['post_id', 'locale', 'title', 'slug', 'content', 'meta_keywords', 'meta_description'];
}
