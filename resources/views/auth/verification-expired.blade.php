@extends('layouts.guest')

@section('content')
<div class="guest-form">
    <div class="login-container">
        <h1>認証リンクの有効期限切れ</h1>

        <p>
            認証リンクの有効期限が切れています。
        </p>

        <p>
            下のボタンから新しい確認メールを送信してください。
        </p>

        @auth
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit">
                    確認メールを再送する
                </button>
            </form>
        @endauth

        <div style="margin-top: 1rem;">
            <a href="{{ route('login') }}" class="link-button">
                ログイン画面へ戻る
            </a>
        </div>
    </div>
</div>
@endsection
