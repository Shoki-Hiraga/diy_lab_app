@extends('layouts.guest')

@section('content')
<div class="guest-form">
    <div class="login-container">
        <h1>確認メールを送信しました</h1>

        <p>
            新しい確認メールを送信しました。
        </p>

        <p>
            メールをご確認のうえ、リンクをクリックしてください。
        </p>

        <div style="margin-top: 1rem;">
            <a href="{{ route('login') }}" class="link-button">
                ログイン画面へ戻る
            </a>
        </div>
    </div>
</div>
@endsection
