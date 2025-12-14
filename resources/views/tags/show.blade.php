@extends('layouts.public')

@section('title', '#'.$tag->name.' の投稿一覧')

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

@section('content')
<div class="post-wrapper">
    <a href="{{ route('tags.index') }}">← タグ一覧へ戻る</a>

    <h2>#{{ $tag->name }} の投稿一覧</h2>

    <div class="post-list">
        @forelse ($posts as $post)
            <div class="post-card">
                {{-- メイン画像 --}}
                @if ($post->main_image_path)
                    <img src="{{ asset('fileassets/'.$post->main_image_path) }}" class="post-image">
                @else
                    <div class="post-image no-image">No Image</div>
                @endif

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

                        {{-- 難易度 --}}
                        <span class="difficulty">
                            {{ str_repeat('★', $post->difficulty_id ?? 0) }}
                        </span>

                        <span class="date">
                            {{ $post->created_at->format('Y/m/d') }}
                        </span>
                    </div>

                    {{-- タイトル --}}
                    <h3 class="post-title">
                        {{ $post->title }}
                    </h3>

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

                    {{-- 詳細 --}}
                    <div class="post-actions">
                        <a href="{{ route('users.posts.show', $post) }}"
                           class="btn-detail">
                            詳細を見る
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="no-posts">このタグの投稿はありません。</p>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
