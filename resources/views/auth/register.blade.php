{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

<x-guest-layout>

    <div class="register-container">
        <h1>新規アカウント作成</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- ユーザー名 -->
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
                @error('username')
                    <p style="color:red; font-size:0.9rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- メールアドレス -->
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <p style="color:red; font-size:0.9rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- パスワード -->
            <div class="form-group">
                <label for="password">パスワード</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
                @error('password')
                    <p style="color:red; font-size:0.9rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- パスワード確認 -->
            <div class="form-group">
                <label for="password_confirmation">パスワード（確認用）</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <button type="submit">
                アカウント作成
            </button>

            <div class="links">
                <a href="{{ route('login') }}">すでにアカウントをお持ちの方はこちら</a>
            </div>
        </form>
    </div>
</x-guest-layout>
