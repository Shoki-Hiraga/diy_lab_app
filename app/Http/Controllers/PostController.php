<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostContent;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // 投稿フォーム
    public function create()
    {
        $categories = Category::all();
        $tools = Tool::all();
        return view('posts.create', compact('categories', 'tools'));
    }

    // 投稿保存
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|max:255',
            'difficulty_id' => 'required|integer|min:1|max:5',
            'category_id'   => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'tools.*'       => 'nullable|exists:tools,id',
            'images.*'      => 'nullable|image|max:4096',
            'comments.*'    => 'nullable|string|max:1000',
        ]);

        // 投稿作成
        $post = Post::create([
            'user_id'       => Auth::id(),
            'title'         => $request->title,
            'difficulty_id' => $request->difficulty_id,
            'status'        => $request->has('draft') ? 'draft' : 'published',
        ]);

        // カテゴリ登録
        $post->categories()->sync($request->category_id);

        // 使用ツール登録
        if ($request->filled('tools')) {
            $post->tools()->sync($request->tools);
        }

        // 画像＋コメント保存
        $images = $request->file('images', []);
        $comments = $request->input('comments', []);

        foreach ($images as $index => $image) {
            $path = $image ? $image->store('posts', 'public') : null;
            $comment = $comments[$index] ?? null;

            $post->contents()->create([
                'image_path' => $path,
                'comment'    => $comment,
                'order'      => $index,
            ]);
        }

        return redirect()->route('posts.create')->with('success', '投稿が完了しました！');
    }
}
