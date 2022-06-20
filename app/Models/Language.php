<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ["lang_code", "name", "is_rtl", "active"];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('active', 1);
        });
    }

}
