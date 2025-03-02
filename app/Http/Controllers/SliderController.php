<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    public function getSliders()
    {
        $sliders = Slider::get()->map(function ($slider) {
            return [
                'id' => $slider->id,
                'title' => $slider->title,
                'subtitle' => $slider->subtitle,
                'description' => $slider->description,
                'price' => $slider->price,
                'image' => $slider->image,
            ];
        });

        return response()->json(['success' => true, 'data' => $sliders, 'message' => 'Sliders fetched successfully']);
    }

    public function store(Request $request){
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('assets/slider'), $imageName);
            $imagePath = '/assets/slider/' . $imageName;
        }

        Slider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
        ]);

        return response()->json(['success' => true, 'message' => 'Slider created successfully']);
    }

    public function update(Request $request)
    {
        $slider = Slider::find($request->id);
        if (!$slider) {
            return response()->json(['success' => false, 'message' => 'Slider not found']);
        }

        $slider->title = $request->title ?? $slider->title;
        $slider->subtitle = $request->subtitle ?? $slider->subtitle;
        $slider->description = $request->description ?? $slider->description;
        $slider->price = $request->price ?? $slider->price;

        if ($request->hasFile('image')) {
            if ($slider->image && file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('assets/slider'), $imageName);
            $slider->image = '/assets/slider/' . $imageName;
        }

        $slider->save();

        return response()->json(['success' => true, 'message' => 'Slider updated successfully', 'data' => $slider]);
    }

    public function delete(Request $request)
    {
        $slider = Slider::find($request->id);
        if (!$slider) {
            return response()->json(['success' => false, 'message' => 'Slider not found']);
        }

        if ($slider->image && file_exists(public_path($slider->image))) {
            unlink(public_path($slider->image));
        }

        $slider->delete();

        return response()->json(['success' => true, 'message' => 'Slider deleted successfully']);
    }
}
