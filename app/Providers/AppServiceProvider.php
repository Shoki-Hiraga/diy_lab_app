<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\Difficulty;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // カテゴリ（公開済み投稿があるものだけ）
        View::share(
            'categories',
            Category::withCount([
                'publishedPosts' => function ($query) {
                    $query->where('status', 'published');
                }
            ])
            ->has('publishedPosts')
            ->get()
        );

        // 難易度（公開済み投稿があるものだけ）
        View::share(
            'difficulties',
            Difficulty::withCount([
                'publishedPosts' => function ($query) {
                    $query->where('status', 'published');
                }
            ])
            ->has('publishedPosts')
            ->get()
        );
    }
}
