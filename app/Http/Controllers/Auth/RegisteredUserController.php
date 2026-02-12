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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 🔥 username チェック
        $existingUsernameUser = User::where('username', $request->username)->first();

        if ($existingUsernameUser) {
            if ($existingUsernameUser->hasVerifiedEmail()) {
                return back()
                    ->withErrors(['username' => 'このユーザー名は既に使用されています。'])
                    ->withInput();
            }

            $existingUsernameUser->delete();
        }

        // 🔥 email チェック
        $existingEmailUser = User::where('email', $request->email)->first();

        if ($existingEmailUser) {
            if ($existingEmailUser->hasVerifiedEmail()) {
                return back()
                    ->withErrors(['email' => 'このメールアドレスは既に登録されています。'])
                    ->withInput();
            }

            $existingEmailUser->delete();
        }

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
