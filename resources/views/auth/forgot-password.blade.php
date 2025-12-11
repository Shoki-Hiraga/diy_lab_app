<x-guest-layout>
    <div class="register-container">
        <h1>パスワード再発行</h1>

        <p class="text-description">
            パスワードを忘れましたか？<br>
            ご登録のメールアドレスを入力すると、パスワード再設定用リンクをお送りします。
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <p style="color:green; font-size:0.9rem; margin-bottom:1rem;">
                {{ session('status') }}
            </p>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- メールアドレス -->
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <p style="color:red; font-size:0.9rem;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">
                パスワード再設定リンクを送信
            </button>

            <div class="links">
                <a href="{{ route('login') }}">ログインページへ戻る</a>
            </div>
        </form>
    </div>
</x-guest-layout>
