@extends('layouts.public')

@section('title', '検索結果')
<meta name="robots" content="noindex">

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

@section('content')

<section class="page-section">

    <h2 class="page-title">検索</h2>

    {{-- =========================
         ▼ 検索条件チップ
         ========================= --}}
    @if(request()->filled('q') || request()->filled('difficulty_id'))
        <div class="search-chips">

            @if(request('q'))
                <a
                    href="{{ route('search.index', request()->except('q')) }}"
                    class="search-chip"
                >
                    「{{ request('q') }}」
                    <span class="chip-close">×</span>
                </a>
            @endif

            @if(request('difficulty_id'))
                <a
                    href="{{ route('search.index', request()->except('difficulty_id')) }}"
                    class="search-chip"
                >
                    難易度 {{ str_repeat('★', request('difficulty_id')) }}
                    <span class="chip-close">×</span>
                </a>
            @endif

        </div>
    @endif

    {{-- =========================
         ▼ 検索フォーム
         ========================= --}}
    <form method="GET" action="{{ route('search.index') }}" class="search-form">

        <div class="search-row">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="キーワード検索"
            >
        </div>

        <div class="search-row">
            <select name="difficulty_id">
                <option value="">難易度を選択</option>
                @foreach ($difficulties as $difficulty)
                    <option
                        value="{{ $difficulty->id }}"
                        @selected(request('difficulty_id') == $difficulty->id)
                    >
                        {{ str_repeat('★', $difficulty->id) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit">検索</button>
    </form>

    {{-- =========================
         ▼ 検索結果一覧
         ========================= --}}
    <div class="post-list">

        @forelse ($posts as $post)

        <article class="post-item">

            {{-- ▼ クリック可能なカード --}}
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
            <p class="no-posts">まだ投稿がありません。</p>
        @endforelse

    </div>

    <div class="pagination-wrapper">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>

</section>

@endsection
