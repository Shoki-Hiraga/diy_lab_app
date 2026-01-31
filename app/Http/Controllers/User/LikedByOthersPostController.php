<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;

class LikedByOthersPostController extends Controller
{
    /**
     * いいねされた投稿一覧（現在有効のみ）
     */
    public function likes()
    {
        $userId = auth()->id();

        $posts = Post::where('user_id', $userId)
            ->whereHas('reactions', function ($q) use ($userId) {
                $q->where('is_active', true)                 // ⭐ 有効のみ
                  ->where('user_id', '!=', $userId)         // ⭐ 自分以外
                  ->whereHas('type', fn ($q) => $q->where('name', 'like'));
            })
            ->withListRelations()
            ->withCommentCount()
            ->latest()
            ->paginate(12);

        return view('users.posts.others.likes', compact('posts'));
    }
}
