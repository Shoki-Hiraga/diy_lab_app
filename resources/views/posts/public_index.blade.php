@extends('layouts.public')

{{-- ページタイトル --}}
@section('title', 'DIY ラボ')

{{-- ページ説明（SEO用） --}}
@section('description', 'DIYの投稿一覧ページです。人気のDIYや最新の投稿をチェックできます。')

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

{{-- ▼ メインコンテンツ --}}
@section('content')

    <section class="page-section">

        <div class="post-list">

            @forelse ($posts as $post)

                <article class="post-card">

                    <a
                        href="{{ route('users.posts.show', $post) }}"
                        class="post-card-link"
                    >

                        {{-- メイン画像 --}}
                        @php
                            $mainImage = optional($post->contents->first())->image_path;
                        @endphp

                        @if ($mainImage)
                            <img
                                src="{{ asset('fileassets/' . $mainImage) }}"
                                alt="{{ $post->title }}"
                                class="post-image"
                            >
                        @else
                            <div class="post-image no-image">
                                No Image
                            </div>
                        @endif

                        {{-- 本文 --}}
                        <div class="post-body">

                            {{-- メタ情報 --}}
                            <div class="post-meta">

                                {{-- カテゴリ --}}
                                <div class="categories">
                                    @foreach ($post->categories as $category)
                                        <span class="category-badge">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>

                                {{-- 難易度 --}}
                                <span class="difficulty">
                                    難易度：
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star">
                                            {{ $i <= ($post->difficulty_id ?? 0) ? '★' : '☆' }}
                                        </span>
                                    @endfor
                                </span>

                                {{-- 投稿日 --}}
                                <span class="date">
                                    {{ $post->created_at->format('Y/m/d') }}
                                </span>

                            </div>

                            {{-- タイトル --}}
                            <h3 class="post-title">
                                {{ $post->title }}
                            </h3>

                            {{-- 本文（抜粋） --}}
                            <p class="post-text">
                                {{ Str::limit($post->content ?? '', 80, '…') }}
                            </p>

                            {{-- 投稿者 --}}
                            <div class="post-author">
                                投稿者：
                                {{ $post->user->username }}
                            </div>

                        </div>
                    </a>

                    {{-- 編集ボタン（カードリンクの外に出す） --}}
                    @auth
                        @if (auth()->id() === $post->user_id)
                            <div class="post-actions">
                                <a
                                    href="{{ route('users.posts.edit', $post) }}"
                                    class="btn-edit"
                                >
                                    編集
                                </a>
                            </div>
                        @endif
                    @endauth

                </article>

            @empty
                <p class="no-posts">
                    まだ投稿がありません。
                </p>
            @endforelse

        </div>

        {{-- ページネーション --}}
        <div class="pagination-wrapper">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>

    </section>

@endsection
