<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReactionPostController extends Controller
{
    /**
     * マイいいね一覧
     */
    public function likes()
    {
        $user = Auth::user();

        $posts = $user->likedPosts()
            ->published()
            ->withListRelations()
            ->withCommentCount()
            ->latest()
            ->paginate(12);

        return view('users.posts.likes', compact('posts', 'user'));
    }

    /**
     * マイブックマーク一覧
     */
    public function bookmarks()
    {
        $user = Auth::user();

        $posts = $user->bookmarkedPosts()
            ->published()
            ->withListRelations()
            ->withCommentCount()
            ->latest()
            ->paginate(12);

        return view('users.posts.bookmarks', compact('posts', 'user'));
    }
}
