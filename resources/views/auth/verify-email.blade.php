@extends('layouts.guest')

@section('content')
<div class="guest-form">
    <div class="login-container">
        <h1>メールアドレス確認</h1>

        <p>
            ご登録ありがとうございます。<br>
            ご登録のメールアドレスに確認メールを送信しました。
        </p>

        <p>
            メール内のリンクをクリックして、登録を完了してください。
        </p>

        @if (session('status') === 'verification-link-sent')
            <p class="success">
                新しい確認メールを送信しました。
            </p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">
                確認メールを再送する
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
            @csrf
            <button type="submit" class="link-button">
                ログアウト
            </button>
        </form>
    </div>
</div>
@endsection
