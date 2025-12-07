<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PostController as UserPostController;
use App\Http\Controllers\User\UserController as UserUserController;
use App\Http\Controllers\User\PostlistController as UserPostlistController;
use App\Http\Controllers\PostPublicController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('users.welcome');
});

Route::get('/posts/{post}', [PostPublicController::class, 'show'])
    ->name('posts.show');

/*
|--------------------------------------------------------------------------
| User Routes (/users/)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('users')->group(function () {

    // 投稿一覧
    Route::get('/', [UserPostlistController::class, 'index'])
        ->name('users.posts.index');

    // 新規投稿
    Route::get('/new', [UserPostController::class, 'create'])
        ->name('users.posts.create');
    Route::post('/new', [UserPostController::class, 'store'])
        ->name('users.posts.store');

    // 編集・更新
    Route::get('/posts/{post}/edit', [UserPostController::class, 'edit'])
        ->name('users.posts.edit');
    Route::put('/posts/{post}', [UserPostController::class, 'update'])
        ->name('users.posts.update');

    // 削除
    Route::delete('/posts/{post}', [UserPostController::class, 'destroy'])
        ->name('users.posts.destroy');

    // プロフィール
    Route::get('/{id}', [UserUserController::class, 'show'])
        ->name('users.profile.show');
    Route::put('/{id}', [UserUserController::class, 'update'])
        ->name('users.profile.update');

});

require __DIR__.'/auth.php';
