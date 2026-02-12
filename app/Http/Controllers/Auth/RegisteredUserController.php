<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * 未認証ユーザーが既に存在する場合は上書きして再送する。
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 同じメールのユーザーを取得
        $existingUser = User::where('email', $request->email)->first();

        // =============================
        // ① 既に認証済みならエラー
        // =============================
        if ($existingUser && $existingUser->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'このメールアドレスは既に登録されています。',
            ])->withInput();
        }

        // =============================
        // ② 未認証なら上書きして再送
        // =============================
        if ($existingUser && !$existingUser->hasVerifiedEmail()) {

            $existingUser->update([
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($existingUser));

            Auth::login($existingUser);

            return redirect()->route('verification.notice');
        }

        // =============================
        // ③ 新規登録
        // =============================
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
