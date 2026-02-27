<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'short_description',
        'description',
        'price',
        'disscount',
        'quantity',
        'main_image',
        'show_trending',
        'show_on_sale',
        'show_best_seller',
        'show_top_rated',
        'show_popular',
        'product_category_id',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function subcategories()
    {
        return $this->belongsToMany(ProductSubcategory::class, 'products_subcategories', 'product_id', 'subcategory_id');
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }

}
