<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="robots" content="noindex">
    <meta charset="utf-8">

    <title>@yield('title', 'DIY投稿サイト')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    {{-- 共通CSS / JS --}}
    @vite([
        'resources/css/app.css',
        'resources/css/common/header.css',
        'resources/css/common/posts-index.base.css',
        'resources/css/common/posts-index.breakpoints.css',
        'resources/css/common/search.css',
        'resources/js/app.js'
    ])

    {{-- 公開用SEO --}}
    <meta name="description" content="@yield('description', 'DIYのアイデア投稿一覧')">
</head>

<body class="layout-public">

    {{-- ▼ ページ専用ヘッダー --}}
    @hasSection('post-header')
        <div class="post-header-wrapper">
            @yield('post-header')
        </div>
    @endif

    {{-- ▼ メインコンテンツ --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- ▼ フッター --}}
    <footer class="public-footer">
        <p>&copy; {{ date('Y') }} DIY SNS</p>
    </footer>

</body>
</html>
