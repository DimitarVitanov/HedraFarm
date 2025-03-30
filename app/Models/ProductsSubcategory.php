<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsSubcategory extends Model
{
    protected $table = 'products_subcategories';
    protected $fillable = [
        'product_id',
        'subcategory_id',
    ];
}
