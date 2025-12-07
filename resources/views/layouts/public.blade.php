<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'DIY投稿サイト')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- 共通CSS --}}
    @vite([
    'resources/css/common/posts-index.css',
    'resources/js/app.js'
    ])

    {{-- 公開用SEO --}}
    <meta name="description" content="@yield('description', 'DIYのアイデア投稿一覧')">
</head>
<body class="layout-public">

    <header class="public-header">
        <div class="header-inner">
            <a href="{{ route('public.posts.index') }}" class="logo">
                DIY SNS
            </a>

            <nav class="public-nav">
                <a href="{{ route('login') }}">ログイン</a>
                <a href="{{ route('register') }}">会員登録</a>
            </nav>
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="public-footer">
        <p>&copy; {{ date('Y') }} DIY SNS</p>
    </footer>
</body>
</html>
