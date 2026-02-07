<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta name="robots" content="noindex">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NSKM2H6J');</script>
    <!-- End Google Tag Manager -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    <title>{{ config('app.name', 'Laravel') }}</title>

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
        'resources/css/common/search.css',
        'resources/css/users/posts-form.base.css',
        'resources/css/users/posts-form.breakpoints.css',
        'resources/css/users/users.css',
        'resources/css/users/dashboard.css',
        'resources/js/app.js'
    ])
</head>

<body class="layout-app">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NSKM2H6J"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    {{-- ▼ ページ専用ヘッダー（public / guest と完全一致） --}}
    @hasSection('post-header')
        <div class="post-header-wrapper">
            <div class="page-section">
                @yield('post-header')
            </div>
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
</body>
</html>
