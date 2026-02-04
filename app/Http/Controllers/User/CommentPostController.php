<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;

class CommentPostController extends Controller
{
    /**
     * 自分がコメントした投稿一覧
     */
    public function comments()
    {
        $userId = auth()->id();

        $posts = Post::whereHas('comments', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->published()
            ->withListRelations()
            ->withCommentCount()
            ->latest()
            ->paginate(12);

        return view('users.posts.comments', compact('posts'));
    }
}
