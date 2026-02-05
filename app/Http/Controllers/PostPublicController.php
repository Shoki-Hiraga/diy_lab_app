<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PostPublicController extends Controller
{
    /**
     * 公開トップ：投稿一覧
     */
    public function index()
    {
        $posts = Post::published()
            ->withListRelations()
            ->withCommentCount()
            ->with(['user.profile', 'contents'])
            ->latest()
            ->paginate(30);

        return view('posts.public_index', compact('posts'));
    }

    /**
     * 公開用 投稿詳細
     */
    public function show(Post $post, Request $request)
    {
        $post = Post::published()
            ->whereKey($post->id)
            ->withCommentCount()
            ->with([
                'categories',
                'tools',
                'tags',
                'contents',
                'user.profile',
                'rootComments.user',
                'rootComments.replies.user',
            ])
            ->firstOrFail();

        // ★ PVカウント判定
        if ($this->shouldCountPv($post, $request)) {
            $post->incrementView();
        }

        return view('posts.public_show', compact('post'));
    }

    /**
     * PVをカウントすべきか判定
     */
    private function shouldCountPv(Post $post, Request $request): bool
    {
        // ----------------------------
        // ① BOT除外
        // ----------------------------
        $userAgent = strtolower($request->userAgent() ?? '');

        $botKeywords = [
            'bot',
            'crawl',
            'spider',
            'slurp',
            'bingpreview',
            'facebookexternalhit',
        ];

        foreach ($botKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return false;
            }
        }

        // ----------------------------
        // ② 同一ユーザー / IP 1日1PV
        // ----------------------------
        $date = Carbon::today()->toDateString();

        if (Auth::check()) {
            $key = "pv:user:" . Auth::id() . ":post:{$post->id}:{$date}";
        } else {
            $ip = $request->ip();
            $key = "pv:guest:{$ip}:post:{$post->id}:{$date}";
        }

        if (Cache::has($key)) {
            return false;
        }

        // 当日いっぱいまで有効
        Cache::put($key, true, now()->endOfDay());

        return true;
    }
}
