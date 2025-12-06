<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\PostController as UserPostController;
use App\Http\Controllers\User\UserController as UserUserController;
use App\Http\Controllers\User\PostlistController as UserPostlistController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| User Routes (/users/)
|--------------------------------------------------------------------------
| 会員向けのユーザー管理・投稿管理
| ※ /users/ 以下はすべてログイン必須
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

    // 投稿編集
    Route::get('/posts/{post}/edit', [UserPostController::class, 'edit'])
        ->name('users.posts.edit');
    Route::put('/posts/{post}', [UserPostController::class, 'update'])
        ->name('users.posts.update');

    // プロフィール
    Route::get('/{id}', [UserUserController::class, 'show'])
        ->name('users.profile.show');
    Route::put('/{id}', [UserUserController::class, 'update'])
        ->name('users.profile.update');
});

require __DIR__.'/auth.php';
