<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOPページ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9fafb;
            color: #333;
            text-align: center;
            padding: 40px;
        }
        h1 { margin-bottom: 40px; }
        .links {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        a {
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #3490dc;
            padding: 12px 24px;
            border-radius: 8px;
            transition: 0.3s;
            width: 260px;
        }
        a:hover { background-color: #2779bd; }
        .small { font-size: 0.9em; color: #555; margin-top: 40px; }
    </style>
</head>
<body>
    <h1>ようこそ！TOPページへ</h1>

    {{-- ログイン状態で表示を切り替え --}}
    @auth
        <div class="links">
            <a href="{{ route('profile.edit') }}">プロフィール編集</a>
            <a href="{{ route('users.posts.create') }}">ユーザー作成（新規投稿）</a>
            <a href="{{ route('users.profile.show', ['id' => 4]) }}">ユーザー詳細（例: ID=4）</a>
        </div>
        <div class="small">
            <p>ログイン中です。</p>
        </div>
    @endauth

    @guest
        <div class="links">
            <a href="{{ route('login') }}">ログイン</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}">ユーザー登録</a>
            @endif
            {{-- 認証後アクセス可能なページ例（そのまま押すとログインへリダイレクト） --}}
            <a href="{{ route('users.profile.edit') }}">プロフィール編集（要ログイン）</a>
            <a href="{{ route('users.posts.create') }}">ユーザー作成（要ログイン）</a>
            <a href="{{ route('users.profile.show', ['id' => 1]) }}">ユーザー詳細（要ログイン）</a>
        </div>
        <div class="small">
            <p>※ 認証が必要なページは、未ログイン時はログイン画面にリダイレクトされます。</p>
        </div>
    @endguest
</body>
</html>
