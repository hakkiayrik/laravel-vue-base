<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, Translatable;

	public $translatedAttributes = ['title', 'slug', 'content', 'meta_keywords', 'meta_description'];
	protected $fillable = ['admin_id', 'status', 'type', 'published_date', 'visibility', 'video_url', 'like', 'dislike'];

    /**
     * Get post admin sets.
     */
    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get post get categories sets.
     */
    public function categories() {
        return $this->morphToMany(Category::class, 'categorizable', 'categorizable');
    }
}
