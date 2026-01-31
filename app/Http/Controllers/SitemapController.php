<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        $categories = Category::all();
        $tags = Tag::all();
        $creators = User::has('posts')->get(); // 投稿があるユーザーのみ

        return response()->view('sitemap', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'creators' => $creators,
        ])->header('Content-Type', 'text/xml');
    }
}