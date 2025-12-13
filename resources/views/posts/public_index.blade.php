@extends('layouts.public')

@section('title', 'DIY 投稿一覧')

@section('content')

<div class="post-wrapper">
    @include('components.header')

    <h2>投稿一覧</h2>

    {{-- 公開用なのでユーザー固定の表示はしない --}}
    <div class="post-list">

        @forelse ($posts as $post)
            <div class="post-card">

                {{-- メイン画像 --}}
                @php
                    $mainImage = optional($post->contents->first())->image_path;
                @endphp

                @if ($mainImage)
                    <img src="{{ asset('fileassets/'.$mainImage) }}"
                         alt="{{ $post->title }}"
                         class="post-image">
                @else
                    <div class="post-image no-image">No Image</div>
                @endif

                {{-- 本文 --}}
                <div class="post-body">
                    <div class="post-meta">

                        {{-- カテゴリ --}}
                        <div class="categories">
                            @foreach ($post->categories as $category)
                                <a href="{{ route('categories.show', $category) }}"
                                class="category-badge">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>


                        {{-- 公開画面なのでステータスは表示しない --}}
                        
                        {{-- 難易度 --}}
                        <span class="difficulty">
                            難易度：
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star">
                                    {{ $i <= ($post->difficulty_id ?? 0) ? '★' : '☆' }}
                                </span>
                            @endfor
                        </span>

                        {{-- 日付 --}}
                        <span class="date">
                            {{ $post->created_at->format('Y/m/d') }}
                        </span>
                    </div>

                    {{-- タイトル --}}
                    <h3 class="post-title">{{ $post->title }}</h3>

                    {{-- 本文 --}}
                    <p class="post-text">
                        {{ Str::limit($post->content ?? '', 80, '…') }}
                    </p>

                    <div class="post-author">
                        投稿者：
                        <a href="{{ route('creators.show', $post->user) }}"
                        class="author-link">
                            {{ $post->user->username }}
                        </a>
                    </div>

                    {{-- 操作ボタン（公開ページなので詳細のみ） --}}
                    <div class="post-actions">
                        <a href="{{ route('users.posts.show', $post) }}"
                           class="btn-detail">
                            詳細を見る
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="no-posts">まだ投稿がありません。</p>
        @endforelse

    </div>

    {{-- ページネーション --}}
    <div class="pagination-wrapper">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
