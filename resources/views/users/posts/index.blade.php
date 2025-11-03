@extends('layouts.app')

@section('content')
<div class="post-wrapper">
    <h2>投稿一覧</h2>

    {{-- ユーザー情報 --}}
    @php
        $iconPath = $user->profile && $user->profile->profile_image_url
            ? asset('assets/icons/'.$user->profile->profile_image_url)
            : asset('assets/images/default_icon.png');
    @endphp

    <div class="user-info">
        <img src="{{ $iconPath }}" alt="ユーザー画像" class="user-icon">
        <span class="username">{{ $user->username }} さんの投稿一覧</span>
    </div>

    @if ($posts->count() > 0)
        <div class="post-list">
            @foreach ($posts as $post)
                <div class="post-card">
                    {{-- 画像 --}}
                    @if($post->main_image_path)
                        <img src="{{ asset('assets/'.$post->main_image_path) }}" alt="{{ $post->title }}" class="post-image">
                    @else
                        <div class="post-image no-image">No Image</div>
                    @endif

                    {{-- 内容 --}}
                    <div class="post-body">
                        <div class="post-meta">
                            <span class="difficulty">
                                難易度：
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star">{{ $i <= ($post->difficulty_id ?? 0) ? '★' : '☆' }}</span>
                                @endfor
                            </span>
                            <span class="date">{{ $post->created_at->format('Y/m/d') }}</span>
                        </div>

                        <h3 class="post-title">{{ $post->title }}</h3>
                        <p class="post-text">
                            {{ Str::limit($post->content, 80, '…') }}
                        </p>

                        <div class="post-actions">
                            <a href="{{ route('users.profile.show', $user->id) }}" class="btn-detail">詳細を見る</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ページネーション --}}
        <div class="pagination-wrapper">
            {{ $posts->links() }}
        </div>
    @else
        <p class="no-posts">まだ投稿がありません。</p>
    @endif
</div>
@endsection
