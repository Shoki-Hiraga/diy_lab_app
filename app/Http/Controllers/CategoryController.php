<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * カテゴリ一覧
     */
    public function index()
    {
        $categories = Category::withCount('posts')->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * カテゴリ別 投稿一覧
     */
    public function show(Category $category)
    {
        $posts = $category->posts()
            ->published()
            ->with(['user', 'contents', 'categories'])
            ->latest()
            ->paginate(10);

        return view('categories.show', compact('category', 'posts'));
    }
}
