<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSelfUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeId = (int) $request->route('id');

        if (!auth()->check() || auth()->id() !== $routeId) {
            return redirect()
                ->route('users.profile.show', auth()->id())
                ->with('error', '他のユーザーのページにはアクセスできません。');
        }

        return $next($request);
    }
}
