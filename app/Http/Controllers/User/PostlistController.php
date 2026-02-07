<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Difficulty;

class PostlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 検索条件
        $keyword      = request('keyword');
        $status       = request('status');
        $tagId        = request('tag_id');
        $difficultyId = request('difficulty_id');
        $categoryId   = request('category_id');

        $posts = Post::query()
            // 自分の投稿のみ
            ->where('user_id', (string) $user->id)

            // タイトル + 本文（post_contents.comment）
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhereHas('contents', function ($q2) use ($keyword) {
                          $q2->where('comment', 'like', "%{$keyword}%");
                      });
                });
            })

            // ステータス
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })

            // タグ
            ->when($tagId, function ($q) use ($tagId) {
                $q->whereHas('tags', function ($t) use ($tagId) {
                    $t->where('tags.id', $tagId);
                });
            })

            // 難易度
            ->when($difficultyId, function ($q) use ($difficultyId) {
                $q->where('difficulty_id', $difficultyId);
            })

            // カテゴリ
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('categories', function ($c) use ($categoryId) {
                    $c->where('categories.id', $categoryId);
                });
            })

            // 並び順
            ->orderByDesc('created_at')

            // ページネーション
            ->paginate(30)
            ->appends(request()->query());

        // セレクトボックス用マスタ
        $tags = Tag::all();
        $categories = Category::all();
        $difficulties = Difficulty::all();

        return view('users.posts.index', compact(
            'posts',
            'user',
            'tags',
            'categories',
            'difficulties'
        ));
    }
}
