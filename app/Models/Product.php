<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    //
    protected $fillable = [
        'name',

        'price',
        'description',
        'material',
        'color',
        'dimension',
        'stock',
        'featured',
        'category_id',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->slug = self::generateUniqueSlug($product->name);
        });
    }

    public static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
