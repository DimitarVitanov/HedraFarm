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
                'product_category_id' => $subcategory->product_category_id,
                'product_category_name' => $subcategory->category->name,
            ];
        });

        return response()->json(['success' => true, 'data' => $subcategories, 'message' => 'Subcategories fetched successfully']);
    }

    public function store(Request $request)
    {
        $subcategory = new ProductSubcategory();
        $subcategory->name = $request->name;
        $subcategory->translated = $request->translated;
        $subcategory->product_category_id = $request->product_category_id;
        $subcategory->save();

        return response()->json(['success' => true, 'data' => $subcategory, 'message' => 'Subcategory created successfully']);
    }

    public function update(Request $request)
    {
        $subcategory = ProductSubcategory::find($request->id);
        if (!$subcategory) {
            return response()->json(['success' => false, 'message' => 'Subcategory not found']);
        }

        if (isset($request->name)) {
            $subcategory->name = $request->name;
        }
        if (isset($request->translated)) {
            $subcategory->translated = $request->translated;
        }
        if (isset($request->product_category_id)) {
            $subcategory->product_category_id = $request->product_category_id;
        }

        $subcategory->save();

        return response()->json(['success' => true, 'data' => $subcategory, 'message' => 'Subcategory updated successfully']);
    }

    public function delete(Request $request)
    {
        $subcategory = ProductSubcategory::find($request->id);
        if (!$subcategory) {
            return response()->json(['success' => false, 'message' => 'Subcategory not found']);
        }

        $subcategory->delete();

        return response()->json(['success' => true, 'message' => 'Subcategory deleted successfully']);
    }
}
