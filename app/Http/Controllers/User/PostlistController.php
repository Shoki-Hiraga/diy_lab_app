<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // ログインユーザーの投稿のみ取得
        $posts = Post::with('contents')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(6); // ページネーション

        return view('users.posts.index', compact('posts', 'user'));
    }
}
