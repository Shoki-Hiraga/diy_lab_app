<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Notification;

class CommentedByOthersPostController extends Controller
{
    /**
     * コメントされた投稿一覧
     */
    public function comments()
    {
        $userId = auth()->id();

        /*
         |----------------------------------------
         | 💬 コメント通知を既読にする
         |----------------------------------------
         */
        Notification::where('user_id', $userId)
            ->where('type', 'comment')
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        /*
         |----------------------------------------
         | 💬 コメントされた投稿一覧
         |----------------------------------------
         */
        $posts = Post::where('user_id', $userId)
            ->whereHas('comments', function ($q) use ($userId) {
                $q->where('user_id', '!=', $userId);
            })
            ->withListRelations()
            ->withCommentCount()
            ->latest()
            ->paginate(12);

        return view('users.posts.others.comments', compact('posts'));
    }
}
