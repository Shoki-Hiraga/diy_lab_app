<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // 投稿フォーム
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    // 投稿保存
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'body'        => 'required',
            'image'       => 'nullable|image|max:2048'
        ]);

        $post = new Post();
        $post->title       = $request->title;
        $post->category_id = $request->category_id;
        $post->body        = $request->body;
        $post->user_id     = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $post->image_path = $path;
        }

        $post->save();

        return redirect()->route('posts.create')->with('success', '投稿が完了しました！');
    }
}
