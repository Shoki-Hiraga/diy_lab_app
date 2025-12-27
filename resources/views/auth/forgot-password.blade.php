@extends('layouts.guest')

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

@section('content')

<div class="guest-form">
    <div class="register-container">
        <h1>パスワード再発行</h1>

        <p class="text-description">
            パスワードを忘れましたか？<br>
            ご登録のメールアドレスを入力すると、パスワード再設定用リンクをお送りします。
        </p>

        {{-- Session Status --}}
        @if (session('status'))
            <p class="status-message">
                {{ session('status') }}
            </p>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- メールアドレス --}}
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <p class="form-error">{{ $message }}</p>
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
</div>

@endsection
