<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // 投稿フォーム
    public function create()
    {
        $categories = Category::all();
        $tools = Tool::all(); // 使用ツールテーブルから取得

        return view('posts.create', compact('categories', 'tools'));
    }

    // 投稿保存
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|max:255',
            'body'          => 'required',
            'difficulty_id' => 'required|integer|min:1|max:5',
            'category_id'   => 'required|array',        // ← 複数カテゴリ対応
            'category_id.*' => 'exists:categories,id',  // 各カテゴリが存在するか確認
            'image'         => 'nullable|image|max:2048'
        ]);

        $post = new Post();
        $post->title         = $request->title;
        $post->body          = $request->body;
        $post->difficulty_id = $request->difficulty_id;
        $post->user_id       = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $post->image_path = $path;
        }

        $post->save();

        // 中間テーブルにカテゴリを登録
        $post->categories()->sync($request->category_id);

        return redirect()->route('posts.create')->with('success', '投稿が完了しました！');
    }

}
