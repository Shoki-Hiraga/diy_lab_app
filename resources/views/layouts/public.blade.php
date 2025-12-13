<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="robots" content="noindex">
    <meta charset="utf-8">
    <title>@yield('title', 'DIY投稿サイト')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- 共通CSS --}}
    @vite([
    'resources/css/app.css',
    'resources/js/app.js'
    ])

    {{-- 公開用SEO --}}
    <meta name="description" content="@yield('description', 'DIYのアイデア投稿一覧')">
</head>
<body class="layout-public">

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="public-footer">
        <p>&copy; {{ date('Y') }} DIY SNS</p>
    </footer>
</body>
</html>
