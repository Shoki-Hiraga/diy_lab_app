<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="robots" content="noindex">
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
        'resources/css/common/posts-index.base.css',
        'resources/css/common/posts-index.breakpoints.css',
        'resources/css/common/posts-comment.base.css',
        'resources/css/common/posts-comment.breakpoints.css',
        'resources/css/common/posts-floating.base.css',
        'resources/css/common/posts-floating.breakpoints.css',
        'resources/css/common/search.css',
        'resources/css/users/posts-form.base.css',
        'resources/css/users/posts-form.breakpoints.css',
        'resources/css/users/users.css',
        'resources/js/app.js'
    ])
</head>

<body class="layout-app">

    {{-- ▼ ページ専用ヘッダー（public / guest と完全一致） --}}
    @hasSection('post-header')
        <div class="post-header-wrapper">
            <div class="page-section">
                @yield('post-header')
            </div>
        </div>
    @endif

    {{-- ▼ メインコンテンツ --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- ナビボタン --}}
    @include('components.common.floating-nav')

</body>
</html>
