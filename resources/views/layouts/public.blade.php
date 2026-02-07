<!DOCTYPE html>
<html lang="ja">
<head>
    @if (config('app.noindex'))
    <!-- staging環境noindex -->
    <meta name="robots" content="noindex">
    @endif

    <!-- 開発中のnoindex -->
    <meta name="robots" content="noindex">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PNGZSLM8');</script>
    <!-- End Google Tag Manager -->

    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('components.seo.ogp')
 
    <title>@yield('title', 'DIY LAB')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    {{-- 共通CSS / JS --}}
    @vite([
        'resources/css/app.css',
        'resources/css/common/header.css',
        'resources/css/common/breadcrumbs.css',
        'resources/css/common/posts-index.base.css',
        'resources/css/common/posts-index.breakpoints.css',
        'resources/css/common/post-card.css',
        'resources/css/common/post-card.breakpoints.css',
        'resources/css/common/posts-comment.base.css',
        'resources/css/common/posts-comment.breakpoints.css',
        'resources/css/common/posts-floating.base.css',
        'resources/css/common/posts-floating.breakpoints.css',
        'resources/css/common/cta-card.css',
        'resources/css/common/search.css',
        'resources/css/common/legal.css',
        'resources/js/app.js'
    ])
    @stack('scripts')

    {{-- 公開用SEO --}}
    <meta name="description" content="@yield('description', 'DIYのアイデア投稿一覧')">
    {{-- クエリパラメータを除いたURLをcanonicalに設定 --}}
    <link rel="canonical" href="{{ url()->current() }}">
</head>

<body class="layout-public">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PNGZSLM8"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    {{-- ▼ ページ専用ヘッダー --}}
    @hasSection('post-header')
        <div class="post-header-wrapper">
            @yield('post-header')
        </div>
    @endif

    {{-- パンくずリスト --}}
    @include('components.common.breadcrumbs')

    {{-- ▼ メインコンテンツ --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- ナビボタン --}}
    @include('components.common.floating-nav')

    {{-- ▼ フッター --}}
    <footer class="public-footer">
    @include('components.common.post-footer')

    <p class="copyright">&copy; {{ date('Y') }} DIY LAB</p>
    </footer>


</body>
</html>
