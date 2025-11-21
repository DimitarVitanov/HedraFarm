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

    public function store(Request $request)
    {
        $category = ProductCategory::create([
            'name' => $request->name,
            'translated' => $request->translated,
        ]);

        return response()->json(['success' => true, 'data' => $category, 'message' => 'Category created successfully']);
    }

    public function update(Request $request)
    {
        $category = ProductCategory::find($request->id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found']);
        }

        if (isset($request->name)) {
            $category->name = $request->name;
        }
        if (isset($request->translated)) {
            $category->translated = $request->translated;
        }

        $category->save();

        return response()->json(['success' => true, 'data' => $category, 'message' => 'Category updated successfully']);
    }

    public function delete(Request $request)
    {
        $category = ProductCategory::find($request->id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found']);
        }

        $category->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }
}
