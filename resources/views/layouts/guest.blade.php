<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="robots" content="noindex">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- 独自CSS -->
    @vite([
        'resources/css/common/header.css',
        'resources/css/users/login.css',
        'resources/css/common/search.css',
        'resources/js/app.js'
    ])
</head>
<body>

    {{-- ▼ ページ専用ヘッダー --}}
    @hasSection('post-header')
        <div class="post-header-wrapper">
            @yield('post-header')
        </div>
    @endif

    <div class="login-wrapper">
        {{ $slot }}
    </div>
</body>
</html>
