@extends('layouts.public')

@section(
    'title',
    '難易度 ' . str_repeat('★', $difficulty->id) . ' の投稿一覧'
)

@section('content')
<div class="post-wrapper">
    <a href="{{ route('difficulties.index') }}">← 難易度一覧へ戻る</a>

    <h2>
        難易度
        <span class="stars">
            {{ str_repeat('★', $difficulty->id) }}
        </span>
        の投稿一覧
    </h2>

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

                    <h3 class="post-title">{{ $post->title }}</h3>

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

                    <div class="post-actions">
                        <a href="{{ route('users.posts.show', $post) }}"
                           class="btn-detail">
                            詳細を見る
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="no-posts">この難易度の投稿はありません。</p>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
