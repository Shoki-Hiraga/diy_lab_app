<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PostController as UserPostController;
use App\Http\Controllers\User\UserController as UserUserController;
use App\Http\Controllers\User\PostlistController as UserPostlistController;
use App\Http\Controllers\PostPublicController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DifficultyController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/userstop', function () {
    return view('users.welcome');
})->name('users.top');

Route::get('/', [PostPublicController::class, 'index'])
    ->name('public.posts.index');

Route::get('/posts/{post}', [PostPublicController::class, 'show'])
    ->name('users.posts.show');

/* カテゴリ */
Route::get('/category', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/category/{category}', [CategoryController::class, 'show'])
    ->name('categories.show');

/* 難易度 */
Route::get('/difficulty', [DifficultyController::class, 'index'])
    ->name('difficulties.index');

Route::get('/difficulty/{difficulty}', [DifficultyController::class, 'show'])
    ->name('difficulties.show');

/* タグ */
Route::get('/tags', [TagController::class, 'index'])
    ->name('tags.index');

Route::get('/tags/{tag}', [TagController::class, 'show'])
    ->name('tags.show');

/* 投稿者（Creators） */
Route::get('/creators', [CreatorController::class, 'index'])
    ->name('creators.index');

Route::get('/creators/{user}', [CreatorController::class, 'show'])
    ->name('creators.show');


Route::get('/search', [SearchController::class, 'index'])
    ->name('search.index');

/*
|--------------------------------------------------------------------------
| Comment Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');
    Route::post('/posts/{post}/reaction/{type}', 
        [ReactionController::class, 'toggle']
        )->name('posts.reaction');

});


/*
|--------------------------------------------------------------------------
| User Routes (/users/)
|--------------------------------------------------------------------------
| ※ ログイン必須
*/
Route::middleware('auth')->prefix('users')->group(function () {

    // ------------------------------------------------------------------
    // 共通ユーザー画面（IDに依存しない）
    // ------------------------------------------------------------------

    // 投稿一覧
    Route::get('/', [UserPostlistController::class, 'index'])
        ->name('users.posts.index');

    // 新規投稿
    Route::get('/new', [UserPostController::class, 'create'])
        ->name('users.posts.create');
    Route::post('/new', [UserPostController::class, 'store'])
        ->name('users.posts.store');

    // 編集・更新（投稿は所有権チェックを Controller / Policy 側で）
    Route::get('/posts/{post}/edit', [UserPostController::class, 'edit'])
        ->name('users.posts.edit');
    Route::put('/posts/{post}', [UserPostController::class, 'update'])
        ->name('users.posts.update');

    // 削除
    Route::delete('/posts/{post}', [UserPostController::class, 'destroy'])
        ->name('users.posts.destroy');

    // ------------------------------------------------------------------
    // 自分自身のユーザー画面（/users/{id} 系）
    // ※ self.user ミドルウェアで「自分以外」を自動リダイレクト
    // ------------------------------------------------------------------
    Route::middleware('self.user')->group(function () {

        // プロフィール
        Route::get('/{id}', [UserUserController::class, 'show'])
            ->name('users.profile.show');

        Route::put('/{id}', [UserUserController::class, 'update'])
            ->name('users.profile.update');

        // ✅ 将来ここに増やすだけでOK
        // Route::get('/{id}/notifications', ...);

    });

});

require __DIR__.'/auth.php';
