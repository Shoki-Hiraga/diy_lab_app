<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Tag;
use Illuminate\Support\Facades\View;
use App\Services\BreadcrumbService;

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
        // --------------------------------------------
        // 共通：カテゴリ（公開済み投稿があるものだけ）
        // --------------------------------------------
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

        // --------------------------------------------
        // 共通：難易度（公開済み投稿があるものだけ）
        // --------------------------------------------
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

        // --------------------------------------------
        // 共通：タグ（公開済み投稿があるものだけ）
        // --------------------------------------------
        View::share(
            'tags',
            Tag::has('publishedPosts')->get()
        );

        // --------------------------------------------
        // ✅ 共通：パンくずリスト
        // --------------------------------------------
        View::composer('*', function ($view) {
            $breadcrumbs = app(BreadcrumbService::class)->generate();
            $view->with('breadcrumbs', $breadcrumbs);
        });
    }
}
