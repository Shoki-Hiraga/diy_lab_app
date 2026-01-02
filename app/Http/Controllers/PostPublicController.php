<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

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
            ->with([
                'categories',
                'tools',
                'tags',
                'contents',
                'user.profile',

                // コメント関連を追加
                'rootComments.user',
                'rootComments.replies.user',
            ])
            ->withCount('comments') // コメント件数
            ->firstOrFail();

        return view('posts.public_show', compact('post'));
    }
}
