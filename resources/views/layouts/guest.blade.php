<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @if (config('app.noindex'))
        <meta name="robots" content="noindex">
    @endif

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PNGZSLM8');</script>
    <!-- End Google Tag Manager -->

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

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
        'resources/css/users/login.css',
        'resources/css/common/header.css',
        'resources/css/common/footer.css',
        'resources/css/common/breadcrumbs.css',
        'resources/css/common/cta-card.css',
        'resources/css/common/posts-floating.base.css',
        'resources/css/common/posts-floating.breakpoints.css',
        'resources/css/common/search.css',
        'resources/js/app.js'
    ])

<div class="post-header-wrapper">
    @yield('post-header', View::make('components.common.post-header'))
</div>
</head>

<body class="layout-guest">

    {{-- ▼ ヘッダー（public と同じ責務） --}}
    @hasSection('post-header')
        <div class="post-header-wrapper">
            <div class="page-section">
                @yield('post-header')
            </div>
        </div>
    @endif

    {{-- パンくずリスト --}}
    @include('components.common.breadcrumbs')

    {{-- ▼ ログイン専用メイン --}}
    <main class="guest-main">
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
