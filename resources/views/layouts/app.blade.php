<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- 独自CSS -->
    @vite(['resources/css/post.css', 'resources/js/app.js'])
</head>
<body>
    @include('layouts.navigation')

    <!-- ページヘッダー -->
    @isset($header)
        <header>
            <div>
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- ページコンテンツ -->
    <main>
        @yield('content')
    </main>
</body>
</html>
