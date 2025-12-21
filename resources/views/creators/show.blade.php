@extends('layouts.public')

@section('title', $user->username . ' の投稿一覧')

@section('post-header')
    @include('components.post-header')
@endsection

@section('content')

<section class="page-section">

    <a href="{{ route('creators.index') }}" class="back-link">
        ← 投稿者一覧へ戻る
    </a>

    <h2 class="page-title">
        {{ $user->username }} の投稿一覧
    </h2>

    <div class="post-list">

        @forelse ($posts as $post)

        <article class="post-item">

            {{-- =========================
                 ▼ クリック可能なカード
                 ========================= --}}
            <div class="post-card">
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

                        {{-- 難易度・投稿日 --}}
                        <div class="post-meta">

                            <span class="difficulty">
                                難易度：
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star">
                                        {{ $i <= ($post->difficulty_id ?? 0) ? '★' : '☆' }}
                                    </span>
                                @endfor
                            </span>

                            <span class="date">
                                {{ $post->created_at->format('Y/m/d') }}
                            </span>

                        </div>

                        <h3 class="post-title">
                            {{ $post->title }}
                        </h3>

                        <p class="post-text">
                            {{ Str::limit($post->content ?? '', 80, '…') }}
                        </p>

                    </div>
                </a>
            </div>

            {{-- =========================
                 ▼ カード外メタ情報
                 ========================= --}}
            <div class="post-meta-outside">

                {{-- カテゴリ --}}
                <div class="categories">
                    <span class="category-label">カテゴリ</span>

                    @foreach ($post->categories->take(1) as $category)
                        <a
                            href="{{ route('categories.show', $category) }}"
                            class="category"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach

                    @if ($post->categories->count() > 3)
                        <span class="category-more">
                            +{{ $post->categories->count() - 3 }}
                        </span>
                    @endif
                </div>

                {{-- 投稿者 --}}
                <div class="post-author">
                    <a
                        href="{{ route('creators.show', $post->user) }}"
                        class="author-link"
                        title="投稿者：{{ $post->user->username }}"
                    >
                        <i class="fa-solid fa-user"></i>
                        <span class="author-name">{{ $post->user->username }}</span>
                    </a>
                </div>


                {{-- 編集ボタン --}}
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

            </div>

        </article>

        @empty
            <p class="no-posts">
                この投稿者の投稿はありません。
            </p>
        @endforelse

    </div>

    <div class="pagination-wrapper">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>

</section>

@endsection
