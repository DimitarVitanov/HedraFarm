<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductSubcategory;

class ProductSubcategoryController extends Controller
{
    public function fetchSubcategories(){
        $subcategories = ProductSubcategory::with('category')->get()->map(function($subcategory){
            return [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
                'translated' => $subcategory->translated,
                'description' => $subcategory->description,
                'product_category_id' => $subcategory->product_category_id,
                'product_category_name' => $subcategory->category->name,
                'is_active' => $subcategory->is_active,
            ];
        });

        return response()->json(['success' => true, 'data' => $subcategories, 'message' => 'Subcategories fetched successfully']);
    }
}
