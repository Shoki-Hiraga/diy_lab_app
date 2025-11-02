<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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
| Dashboard (認証済みユーザー向け)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes (認証必須)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
    // --- ログイン中ユーザーの投稿一覧 ---
    Route::get('/', [UserPostlistController::class, 'index'])->name('users.posts.index');

    // --- 投稿関連 ---
    Route::get('/new', [PostController::class, 'create'])->name('posts.create');
    Route::post('/new', [PostController::class, 'store'])->name('posts.store');

    // --- プロフィール関連 ---
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
});

require __DIR__.'/auth.php';
