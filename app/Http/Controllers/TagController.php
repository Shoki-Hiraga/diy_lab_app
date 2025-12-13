<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * タグ一覧
     */
    public function index()
    {
        /**
         * 公開済み投稿が1件以上あるタグのみ取得
         * published_posts_count を同時に取得
         */
        $tags = Tag::withCount('publishedPosts')
            ->has('publishedPosts')
            ->orderByDesc('published_posts_count')
            ->get();

        return view('tags.index', compact('tags'));
    }

    /**
     * タグ別 投稿一覧
     */
    public function show(Tag $tag)
    {
        /**
         * 指定タグに紐づく「公開済み投稿」のみ取得
         */
        $posts = $tag->publishedPosts()
            ->with(['user', 'categories', 'tags', 'difficulty'])
            ->latest()
            ->paginate(10);

        return view('tags.show', compact('tag', 'posts'));
    }
}
