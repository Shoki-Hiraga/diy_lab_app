<?php

namespace App\Http\Controllers;

use App\Models\User;

class CreatorController extends Controller
{
    /**
     * 投稿者一覧
     */
    public function index()
    {
        /**
         * ・アクティブユーザーのみ
         * ・公開投稿が1件以上あるユーザーのみ
         */
        $creators = User::where('is_active', true)
            ->withCount('publishedPosts')
            ->has('publishedPosts')
            ->orderByDesc('published_posts_count')
            ->get();

        return view('creators.index', compact('creators'));
    }

    /**
     * 投稿者別 投稿一覧
     */
    public function show(User $user)
    {
        // 非アクティブユーザーは404
        abort_if(!$user->is_active, 404);

        $posts = $user->publishedPosts()
            ->withListRelations()
            ->withCommentCount()
            ->with(['categories', 'tags', 'difficulty'])
            ->latest()
            ->paginate(10);

        return view('creators.show', compact('user', 'posts'));
    }
}
