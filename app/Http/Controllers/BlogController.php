<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Auth;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class BlogController extends Controller
{

    public function fetchBlog(){
        $blogs = Blog::with('user')->orderBy('id','DESC')->get()->map(function($blog){
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'short_description' => mb_substr($blog->content, 0, 250) . '...',
                'content' => $blog->content,
                'image' => $blog->image,
                'user' => $blog->user->name,
                'user_id' => $blog->user->id,
                'date' => Carbon::parse($blog->created_at)->format('Y-m-d')
            ];
        });

        return response()->json(['success' => true, 'data' => $blogs, 'message' => 'Blogs fetched successfully']);
    }
    public function store(Request $request){
        $imagePath = null;
        if($request->hasFile('image')){
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('assets/img/blog'), $imageName);
            $imagePath = '/img/blog/' . $imageName;
        }
        $blog = new Blog();
        $blog->user_id = Auth::user()->id;
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->image = $imagePath;
        $blog->save();

        return response()->json(['success' => true, 'message' => 'Blog created successfully']);
    }

    public function update(Request $request)
    {
        $blog = Blog::find($request->id);
        if(!$blog){
            return response()->json(['success' => false, 'message' => 'Blog not found']);
        }
        if(isset($request->title)){
            $blog->title = $request->title;
        }
        if(isset($request->content)){
            $blog->content = $request->content;
        }
        if($request->hasFile('image')){
            if($blog->image && file_exists(public_path($blog->image))){
                unlink(public_path($blog->image));
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('assets/img/blog'), $imageName);
            $blog->image = '/img/blog/' . $imageName;
        }
        $blog->save();

        return response()->json(['success' => true, 'message' => 'Blog updated successfully']);
    }

    public function delete(Request $request)
    {
        $blog = Blog::find($request->id);
        if(!$blog){
            return response()->json(['success' => false, 'message' => 'Blog not found']);
        }
        $blog->delete();

        return response()->json(['success' => true, 'message' => 'Blog deleted successfully']);
    }

    public function viewBlog($id)
    {
        $blog = Blog::whereId($id)->with('user')->first();
        if(!$blog){
            return response()->json(['success' => false, 'data' => null, 'message' => 'Blog not found']);
        }
        $data = (object) [
            'id' => $blog->id,
            'title' => $blog->title,
            'short_description' => mb_substr($blog->content, 0, 150),
            'content' => $blog->content,
            'image' => $blog->image,
            'user' => $blog->user->name,
            'user_id' => $blog->user->id,
            'date' => Carbon::parse($blog->created_at)->format('Y-m-d')
        ];
        return Inertia::render('Blog/View', ['data' => $data]);
    }

    public function getBlog($id){
        $blog = Blog::whereId($id)->with('user')->first();
        if(!$blog){
            return response()->json(['success' => false, 'message' => 'Blog not found']);
        }
        $data = (object) [
            'id' => $blog->id,
            'title' => $blog->title,
            'short_description' => substr($blog->content, 0, 150),
            'content' => $blog->content,
            'image' => $blog->image,
            'user' => $blog->user->name,
            'user_id' => $blog->user->id,
            'date' => Carbon::parse($blog->created_at)->format('Y-m-d')
        ];

        return response()->json(['success' => true, 'data' => $data, 'message' => 'Blog fetched successfully']);
    }

}
