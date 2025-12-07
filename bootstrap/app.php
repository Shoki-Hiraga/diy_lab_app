<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ★ 追加：自分用ミドルウェア
use App\Http\Middleware\EnsureSelfUser;
use App\Http\Middleware\RedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    | Laravel 11 では Http/Kernel.php は使われません。
    | この withMiddleware() で alias をすべて定義します。
    */
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            // 未ログイン only（login / register 用）
            'guest'     => RedirectIfAuthenticated::class,

            // 🔥 自分自身の /users/{id} 専用
            'self.user' => EnsureSelfUser::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
