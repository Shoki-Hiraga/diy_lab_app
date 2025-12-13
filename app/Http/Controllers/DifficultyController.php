<?php

namespace App\Http\Controllers;

use App\Models\Difficulty;
use App\Models\Post;

class DifficultyController extends Controller
{
    /**
     * 難易度一覧
     */
    public function index()
    {
        /**
         * 公開済み投稿がある難易度のみ取得
         * 件数は published_posts_count として取得
         */
        $difficulties = Difficulty::withCount('publishedPosts')
            ->has('publishedPosts')
            ->get();

        return view('difficulties.index', compact('difficulties'));
    }

    /**
     * 難易度別 投稿一覧
     */
    public function show(Difficulty $difficulty)
    {
        /**
         * 指定した難易度の「公開済み投稿」のみ取得
         */
        $posts = Post::where('difficulty_id', $difficulty->id)
            ->where('status', 'published')
            ->with(['categories', 'user'])
            ->latest()
            ->paginate(10);

        return view('difficulties.show', compact('difficulty', 'posts'));
    }
}
