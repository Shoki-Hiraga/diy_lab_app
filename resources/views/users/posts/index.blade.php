@extends('layouts.app')

@section('content')
<div class="post-wrapper">
    <h2>投稿一覧</h2>

    {{-- ユーザー情報 --}}
    @php
        $iconPath = $user->profile && $user->profile->profile_image_url
            ? asset('fileassets/icons/'.$user->profile->profile_image_url)
            : asset('fileassets/images/default_icon.png');
    @endphp

    <div class="user-info">
        <img src="{{ $iconPath }}" alt="ユーザー画像" class="user-icon">
        <span class="username">{{ $user->username }} さんの投稿一覧</span>
    </div>

    @if ($posts->count() > 0)
        <div class="post-list">

            @foreach ($posts as $post)
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

                            {{-- ステータス --}}
                            @if ($post->status === \App\Models\Post::STATUS_DRAFT)
                                <span class="badge badge-draft">下書き</span>
                            @elseif ($post->status === \App\Models\Post::STATUS_PUBLISHED)
                                <span class="badge badge-published">公開中</span>
                            @endif

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

                        {{-- 本文（無くてもOK） --}}
                        <p class="post-text">
                            {{ Str::limit($post->content ?? '', 80, '…') }}
                        </p>

                        {{-- 操作ボタン --}}
                        <div class="post-actions">
                            @if ($post->status === \App\Models\Post::STATUS_DRAFT)
                                <a href="{{ route('users.posts.edit', $post) }}"
                                   class="btn-edit">
                                    下書きを編集
                                </a>
                            @else
                                <a href="{{ route('users.posts.show', $post) }}"
                                   class="btn-detail">
                                    詳細を見る
                                </a>
                                <a href="{{ route('users.posts.edit', $post) }}"
                                   class="btn-edit">
                                    編集
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- ページネーション --}}
        <div class="pagination-wrapper">
            {{ $posts->links('pagination::bootstrap-5') }}

        </div>
    @else
        <p class="no-posts">まだ投稿がありません。</p>
    @endif
</div>
@endsection
