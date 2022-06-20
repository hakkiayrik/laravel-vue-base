<?php

namespace App\Models;

use App\Scopes\DisplayOrderScope;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Translatable;

	public $useTranslationFallback = true;
    public $translatedAttributes = ['name', 'locale', 'slug', 'description'];
    protected $fillable = [
    	"display_order", "status"
	];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new DisplayOrderScope());
    }

}
