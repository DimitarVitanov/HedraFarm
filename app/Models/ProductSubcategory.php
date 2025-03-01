<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSubcategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_subcategories');
    }
}
