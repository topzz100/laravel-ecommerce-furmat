<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    //
    protected $fillable = [
        'title'
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = self::generateUniqueSlug($category->title);
        });

        static::updating(function ($category) {
            if ($category->isDirty('title')) {
                $category->slug = self::generateUniqueSlug($category->title);
            }
        });
    }

    protected static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = self::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
