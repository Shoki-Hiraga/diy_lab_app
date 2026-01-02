@extends('layouts.public')

@section('title', $post->title . '｜DIY投稿')

@section('description', Str::limit(
    optional($post->contents->first())->comment ?? '',
    120
))

@section('post-header')
    @include('components.post-header')
@endsection

@section('content')
<div class="post-wrapper">

    {{-- =====================
        投稿情報
    ===================== --}}
    <h2 class="post-title">{{ $post->title }}</h2>

    @php
        $iconPath = $post->user->profile && $post->user->profile->profile_image_url
            ? asset('fileassets/icons/'.$post->user->profile->profile_image_url)
            : asset('fileassets/images/default_icon.png');
    @endphp

    <div class="user-info">
        <a href="{{ route('creators.show', $post->user) }}" class="user-icon-link">
            <img src="{{ $iconPath }}" class="user-icon">
        </a>

        <div class="user-text">
            <span class="username">
                投稿者：
                <a href="{{ route('creators.show', $post->user) }}">
                    {{ $post->user->username }}
                </a>
            </span>
            <span class="date">
                投稿日：{{ $post->created_at->format('Y/m/d') }}
            </span>
        </div>
    </div>

    {{-- =====================
        本文
    ===================== --}}
    <div class="post-contents">
        @foreach ($post->contents as $content)
            <div class="post-content">
                @if ($content->image_path)
                    <img src="{{ asset('fileassets/'.$content->image_path) }}" class="post-image">
                @endif

                @if ($content->comment)
                    <p class="post-text">{{ $content->comment }}</p>
                @endif
            </div>
        @endforeach
    </div>

    {{-- =====================
        アクション
    ===================== --}}
    <div class="post-actions">
        <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn-back">戻る</a>

        @auth
            @if (auth()->id() === $post->user_id)
                <a href="{{ route('users.posts.edit', $post) }}" class="btn-edit">
                    編集する
                </a>
            @endif
        @endauth
    </div>

    {{-- =====================
        コメント（AJAX）
    ===================== --}}
    <div class="comments" data-post-id="{{ $post->id }}">

        <h3 class="comment-title">
            コメント（<span id="comment-count">{{ $post->comments_count }}</span>件）
        </h3>

        {{-- コメント一覧 --}}
        <div id="comment-list" class="comment-list">
            @foreach ($post->rootComments as $comment)
                @include('components.comments.item', ['comment' => $comment])
            @endforeach
        </div>

        {{-- コメント投稿 --}}
        <div class="comment-form">
            @auth
                <form id="comment-form">
                    @csrf
                    <textarea name="body"
                              rows="4"
                              required
                              class="comment-textarea"
                              placeholder="コメントを入力してください"></textarea>

                    <button type="submit" class="comment-submit">
                        投稿する
                    </button>
                </form>
            @else
                <div class="comment-login-guide">
                    <p class="comment-login-text">
                        ログインするとコメントできます
                    </p>
                    <div class="comment-login-actions">
                        <a href="{{ route('login') }}" class="btn-nav">
                            🔑 ログイン
                        </a>
                        <a href="{{ route('register') }}" class="btn-register">
                            ✨ 会員登録
                        </a>
                    </div>
                </div>
                </div>
            @endauth
        </div>

    </div>

</div>
@endsection

{{-- コメント用JS --}}
@vite(['resources/js/comments.js'])
