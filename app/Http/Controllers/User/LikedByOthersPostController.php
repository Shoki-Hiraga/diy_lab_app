<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Notification;

class LikedByOthersPostController extends Controller
{
    /**
     * いいねされた投稿一覧（現在有効のみ）
     */
    public function likes()
    {
        $userId = auth()->id();

        /*
        |----------------------------------------
        | 👍 いいね通知を既読にする
        |----------------------------------------
        */
        Notification::where('user_id', $userId)
            ->where('type', 'like')
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        /*
        |----------------------------------------
        | 👍 いいねされた投稿一覧
        |----------------------------------------
        */
        $posts = Post::where('user_id', $userId)
            ->whereHas('reactions', function ($q) use ($userId) {
                $q->where('is_active', true)
                ->where('user_id', '!=', $userId)
                ->whereHas('type', fn ($q) => $q->where('name', 'like'));
            })
            ->withListRelations()
            ->withCommentCount()
            ->latest()
            ->paginate(12);

        return view('users.posts.others.likes', compact('posts'));
    }
}
