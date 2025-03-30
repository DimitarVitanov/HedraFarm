<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function getCategories()
    {
        $categories = ProductCategory::get()->map(function($category){
            return [
                'id' => $category->id,
                'translated' => $category->translated,
                'name' => $category->name,
            ];
        });

        return response()->json(['success' => true, 'data' => $categories , 'message' => 'Categories fetched successfully']);
    }
}
