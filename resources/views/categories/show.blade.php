@extends('layouts.public')

@section('title', $category->name . ' の投稿一覧')

@section('content')
<div class="post-wrapper">
    <a href="{{ route('categories.index') }}">← カテゴリ一覧へ戻る</a>

    <h2>{{ $category->name }} の投稿一覧</h2>

    <div class="post-list">
        @forelse ($posts as $post)
            <div class="post-card">
                {{-- メイン画像 --}}
                @if ($post->main_image_path)
                    <img src="{{ asset('assets/'.$post->main_image_path) }}" class="post-image">
                @else
                    <div class="post-image no-image">No Image</div>
                @endif

                <div class="post-body">
                    <div class="post-meta">
                        {{-- カテゴリ複数 --}}
                        <div class="categories">
                            @foreach ($post->categories as $cat)
                                <a href="{{ route('categories.show', $cat) }}" class="category-badge">
                                    {{ $cat->name }}
                                </a>
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

                    {{-- 投稿者 --}}
                    <div class="post-author">
                        投稿者：{{ $post->user->username }}
                    </div>

                    {{-- 詳細ボタン --}}
                    <div class="post-actions">
                        <a href="{{ route('users.posts.show', $post) }}"
                           class="btn-detail">
                            詳細を見る
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="no-posts">このカテゴリの投稿はありません。</p>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
