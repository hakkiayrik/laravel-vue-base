<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, Translatable;

	public $translatedAttributes = ['title', 'slug', 'content', 'meta_keywords', 'meta_description'];
	protected $fillable = ['user_id', 'status', 'type', 'published_date', 'visibility', 'video_url', 'like', 'dislike'];
}
