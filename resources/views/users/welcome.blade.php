@extends('layouts.app')

@section('title', 'マイページ')
@section('description', 'ユーザーのマイページです。投稿管理やお気に入りを確認できます。')

@section('post-header')
    @include('components.common.post-header')
@endsection

@section('content')
<section class="page-section">
    <div class="post-wrapper">

        {{-- =========================
             ▼ ユーザー情報
             ========================= --}}
        @auth
            @php
                $iconPath = Auth::user()->profile && Auth::user()->profile->profile_image_url
                    ? asset('fileassets/icons/' . Auth::user()->profile->profile_image_url)
                    : asset('fileassets/images/default_icon.png');
            @endphp

            <div class="user-info">

                {{-- ユーザーアイコン --}}
                <a
                    href="{{ route('users.profile.show', Auth::id()) }}"
                    class="user-icon-link"
                    title="プロフィールを見る"
                >
                    <img
                        src="{{ $iconPath }}"
                        alt="{{ Auth::user()->username }}"
                        class="user-icon"
                    >
                </a>

                {{-- ユーザー名 --}}
                <div class="user-text">
                    <span class="username">
                        <strong>{{ Auth::user()->username }}</strong>
                    </span>
                    <span class="date">
                        マイページ
                    </span>
                </div>

            </div>
        @endauth

        {{-- =========================
             ▼ マイページメニュー
             ========================= --}}
        @auth
            <h2>MYページ</h2>

            <ul class="type-list">

                <li class="type-item">
                    <a href="{{ route('users.posts.index') }}">
                        <span>自分の投稿一覧</span>
                        <span class="type-count">→</span>
                    </a>
                </li>

                <li class="type-item">
                    <a href="{{ route('users.posts.create') }}">
                        <span>新規投稿</span>
                        <span class="type-count">＋</span>
                    </a>
                </li>

                <li class="type-item">
                    <a href="{{ route('users.likes') }}">
                        <span>いいね一覧</span>
                        <span class="type-count">♥</span>
                    </a>
                </li>

                <li class="type-item">
                    <a href="{{ route('users.bookmarks') }}">
                        <span>ブックマーク一覧</span>
                        <span class="type-count">★</span>
                    </a>
                </li>

                <li class="type-item">
                    <a href="{{ route('users.profile.show', Auth::id()) }}">
                        <span>プロフィール詳細</span>
                        <span class="type-count">👤</span>
                    </a>
                </li>

            </ul>
        @endauth

        {{-- =========================
             ▼ ゲスト表示
             ========================= --}}
        @guest
            <h2>ログインが必要です</h2>

            <p class="no-posts">
                マイページを利用するにはログインしてください
            </p>

            <ul class="type-list" style="margin-top: 1rem;">
                <li class="type-item">
                    <a href="{{ route('login') }}">
                        <span>ログイン</span>
                        <span class="type-count">→</span>
                    </a>
                </li>

                @if (Route::has('register'))
                <li class="type-item">
                    <a href="{{ route('register') }}">
                        <span>ユーザー登録</span>
                        <span class="type-count">＋</span>
                    </a>
                </li>
                @endif
            </ul>
        @endguest

    </div>
</section>
@endsection
