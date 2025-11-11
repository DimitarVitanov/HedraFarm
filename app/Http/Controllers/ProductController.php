<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ClientHelper;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductGallery;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function getProducts()
    {
        $products = Product::with('category')->get()->map(function($product){
            return [
                'id' => $product->id,
                'name' => $product->name,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'clean_description' => Str::limit(strip_tags($product->description), 100),
                'price' => $product->price,
                'disscount' => $product->disscount ?? null,
                'product_category_id' => $product->product_category_id,
                'product_category_name' =>ClientHelper::tidyName($product->category->name),
                'quantity' => $product->quantity,
                'category' => $product->category->name,
                'main_image' =>  $product->main_image,
                'is_active' => (bool)$product->is_active,
                'show_trending' => (bool) $product->show_trending,
                'show_on_sale' => (bool)$product->show_on_sale,
                'show_best_seller' => (bool)$product->show_best_seller,
                'show_top_rated' => (bool)$product->show_top_rated,
                'galleries'=> $product->galleries ?? [],
                'subcategories' => $this->fetchSubcategories($product->id)
            ];
        });

        return response()->json(['success' => true, 'data' => $products, 'message' => 'Products fetched successfully']);
    }

    public function viewProduct($id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
        $product->galleries = $product->galleries ?? [];
        $product->subcategories = $this->fetchSubcategories($id);
        return Inertia::render('Product/View', [
            'data' => $product,
        ]);
    }

    private function fetchSubcategories($productId)
    {
        $product = Product::with('subcategories')->find($productId);
        if (!$product) {
            return [];
        }
        $subcategories =  $product->subcategories->map(function($subcategory){
                return [
                    'id' => $subcategory->id,
                    'translated' => $subcategory->translated,
                    'name' => $subcategory->name,
                ];
            });

        return $subcategories;
    }

    public function getProductsByCategory($id)
    {
        $products = Product::where('product_category_id', $id)->where('is_active',1)->with('category')->get()->map(function($product){
            return [
                'id' => $product->id,
                'name' => $product->name,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'clean_description' => Str::limit(strip_tags($product->description), 100),
                'price' => $product->price,
                'disscount' => $product->disscount ?? null,
                'product_category_id' => $product->product_category_id,
                'quantity' => $product->quantity,
                'category' => $product->category->name,
                'main_image' =>  $product->main_image,
                'is_active' => (bool)$product->is_active,
                'show_trending' => (bool) $product->show_trending,
                'show_on_sale' => (bool)$product->show_on_sale,
                'show_best_seller' => (bool)$product->show_best_seller,
                'show_top_rated' => (bool)$product->show_top_rated,
            ];
        });
        return response()->json(['success' => true, 'data' => $products, 'message' => 'Products fetched successfully']);
    }

    public function store(Request $request)
    {
        $imagePath = null;
        if ($request->hasFile('main_image')) {
            $imageName = time() . '_' . $request->file('main_image')->getClientOriginalName();
            $request->file('main_image')->move(public_path('assets/products'), $imageName);
            $imagePath = '/assets/products/' . $imageName;
        }

        $product = Product::create([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'disscount' => $request->disscount,
            'quantity' => $request->quantity,
            'main_image' => $imagePath,
            'show_trending' => $request->show_trending == 'true' ? 1 : 0,
            'show_on_sale' => $request->show_on_sale == 'true' ? 1 : 0,
            'show_best_seller' => $request->show_best_seller == 'true' ? 1 : 0,
            'show_top_rated' => $request->show_top_rated == 'true' ? 1 : 0,
            'is_active' => $request->is_active == 'true' ? 1 : 0,
            'product_category_id' => $request->product_category_id,
        ]);
        if ($request->has('subcategories') && is_array($request->subcategories)) {
            $product->subcategories()->sync($request->input('subcategories', []));
        }else{
            $product->subcategories()->sync([]);
        }

        if($request->galleries){
            foreach ($request->file('galleries') as $gallery) {
                $galleryName = time() . '_' . $gallery->getClientOriginalName();
                $gallery->move(public_path('assets/products'), $galleryName);
                $galleryImages[] = [
                    'product_id' => $product->id,
                    'image' => '/assets/products/' . $galleryName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            ProductGallery::insert($galleryImages);
        }
        return response()->json(['success' => true, 'message' => 'Product created successfully']);
    }

    public function update(Request $request)
    {
        $product = Product::find($request->id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        if($request->product_category_id){
            $product->product_category_id = $request->product_category_id;
        }
        if($request->name){
            $product->name = $request->name;
        }
        if($request->short_description){
            $product->short_description = $request->short_description;
        }
        if($request->description){
            $product->description = $request->description;
        }
        if($request->price){
            $product->price = $request->price;
        }
        if($request->disscount && $request->disscount != 'null'){
            $product->disscount = $request->disscount;
        }
        if($request->quantity){
            $product->quantity = $request->quantity;
        }
        if($request->show_trending){
            $product->show_trending = $request->show_trending == 'true' || $request->show_trending == 1 ? 1 : 0;
        }
        if($request->show_on_sale){
            $product->show_on_sale = $request->show_on_sale == 'true' || $request->show_on_sale == 1 ? 1 : 0;
        }
        if($request->show_best_seller){
            $product->show_best_seller = $request->show_best_seller == 'true' || $request->show_best_seller == 1 ? 1 : 0;
        }
        if($request->show_top_rated){
            $product->show_top_rated = $request->show_top_rated == 'true' || $request->show_top_rated == 1 ? 1 : 0;
        }
        if($request->is_active){
            $product->is_active = $request->is_active == 'true' || $request->is_active == 1 ? 1 : 0;
        }

        if ($request->hasFile('main_image')) {
            if ($product->main_image && file_exists(public_path($product->main_image))) {
                unlink(public_path($product->main_image));
            }

            $imageName = time() . '_' . $request->file('main_image')->getClientOriginalName();
            $request->file('main_image')->move(public_path('assets/products'), $imageName);
            $product->main_image = '/assets/products/' . $imageName;
        }

        $product->save();

        if ($request->has('subcategories') && is_array($request->subcategories)) {
            $product->subcategories()->sync($request->input('subcategories', []));
        }else{
            $product->subcategories()->sync([]);
        }

        if($request->galleries){
            foreach ($request->file('galleries') as $gallery) {
                $galleryName = time() . '_' . $gallery->getClientOriginalName();
                $gallery->move(public_path('assets/products'), $galleryName);
                $galleryImages[] = [
                    'product_id' => $product->id,
                    'image' => '/assets/products/' . $galleryName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            ProductGallery::insert($galleryImages);
        }
        return response()->json(['success' => true, 'message' => 'Product updated successfully', 'data' => $product]);
    }
    public function delete(Request $request)
    {
        $product = Product::find($request->id);
        $message = 'Product deleted successfully';
        if(!$product){
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
        if($request->type == 'main_image'){
            if ($product->main_image && file_exists(public_path($product->main_image))) {
                unlink(public_path($product->main_image));
            }
            $product->main_image = null;
            $message = 'Main image deleted successfully';
            $product->save();
        }else if($request->type == 'gallery'){
            $gallery = ProductGallery::find($request->gallery_id);
            if($gallery){
                if ($gallery->image && file_exists(public_path($gallery->image))) {
                    unlink(public_path($gallery->image));
                }
                $gallery->delete();
                $message = 'Gallery image deleted successfully';
            }
        }

        else{
            $product->delete();
        }
        return response()->json(['success' => true, 'message' => $message]);
    }

    public function getProductsByType($type){

        $products = $this->formatTypeProducts($type);
        if($products->isEmpty()){
            return response()->json(['success' => false, 'message' => 'No products found']);
        }
        $products = $products->map(function($product){
            return [
                'id' => $product->id,
                'label' => null,
                'img' =>  $product->main_image,
                'title' => $product->name,
                'price' => $product->price,
                'disscount' => $product->disscount ?? null,
                'category_name' => $product->category->translated,
            ];
        });

        return response()->json(['success' => true, 'data' => $products, 'message' => 'Products fetched successfully']);
    }

    private function formatTypeProducts($type){
        $products = [];
        switch($type){
            case 'trending':
                $products = Product::where('show_trending', 1)->where('is_active', 1)->with('category')->get();
                break;
            case 'on-sale':
                $products = Product::where('show_on_sale', 1)->where('is_active', 1)->with('category')->get();
                break;
            case 'best-seller':
                $products = Product::where('show_best_seller', 1)->where('is_active', 1)->with('category')->get();
                break;
            case 'top-rated':
                $products = Product::where('show_top_rated', 1)->where('is_active', 1)->with('category')->get();
                break;
            case 'popular':
                $products = Product::where('is_active', 1)->with('category')
                 ->orderByDesc('quantity')
                 ->take(4)->get();
                 break;
            default:
                $products = Product::where('is_active', 1)->with('category')->get();
                break;
        }
        return $products;
    }

}
