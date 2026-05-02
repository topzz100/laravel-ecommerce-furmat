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
          static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = self::generateUniqueSlug($product->name, $product->id);
            }
        });
    }

      protected static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);

        $query = self::where('slug', 'LIKE', "{$slug}%");

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $count = $query->count();

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
