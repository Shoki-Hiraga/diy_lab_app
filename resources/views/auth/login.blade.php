{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

<x-guest-layout>

    <div class="login-container">
        <h1>DIY LAB ログイン</h1>

        <!-- ログインフォーム -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <p style="color:red; font-size:0.9rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <p style="color:red; font-size:0.9rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember">
                    ログイン情報を記憶する
                </label>
            </div>

            <button type="submit">
                ログイン
            </button>

            <div class="links">
                <a href="{{ route('password.request') }}">パスワードをお忘れですか？</a><br>
                <a href="{{ route('register') }}">新規会員登録はこちら</a>
            </div>
        </form>
    </div>
</x-guest-layout>
