<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostPublicController extends Controller
{
    /**
     * 公開トップ：投稿一覧
     */
    public function index()
    {
        $posts = Post::published()
            ->with(['user.profile', 'contents'])
            ->latest()
            ->paginate(12);

        return view('posts.public_index', compact('posts'));
    }

    /**
     * 公開用 投稿詳細
     */
    public function show(Post $post)
    {
        $post = Post::published()
            ->whereKey($post->id)
            ->with(['categories', 'tools', 'contents', 'user.profile'])
            ->firstOrFail();

        return view('posts.public_show', compact('post'));
    }
}
