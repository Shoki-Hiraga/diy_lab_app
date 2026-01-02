@extends('layouts.public')

@section('title', $post->title . '｜DIY投稿')

@section('description', Str::limit(
    optional($post->contents->first())->comment ?? '',
    120
))

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

@section('content')
<div class="post-wrapper">

    {{-- タイトル --}}
    <h2 class="post-title">{{ $post->title }}</h2>

    {{-- 投稿者情報 --}}
    @php
        $iconPath = $post->user->profile && $post->user->profile->profile_image_url
            ? asset('fileassets/icons/'.$post->user->profile->profile_image_url)
            : asset('fileassets/images/default_icon.png');
    @endphp

    <div class="user-info">
        <a href="{{ route('creators.show', $post->user) }}"
        class="user-icon-link">
            <img src="{{ $iconPath }}"
                alt="{{ $post->user->username }}のプロフィール画像"
                class="user-icon"
                onerror="this.src='{{ asset('static/images/default_icon.png') }}'">
        </a>

        <div class="user-text">
            <span class="username">
                投稿者：
                <a href="{{ route('creators.show', $post->user) }}"
                class="author-link">
                    {{ $post->user->username }}
                </a>
            </span>
            <span class="date">
                投稿日：{{ $post->created_at->format('Y/m/d') }}
            </span>
        </div>
    </div>

    {{-- メタ情報 --}}
    <div class="post-meta">

        {{-- 難易度 --}}
        <span class="difficulty">
            難易度：
            @for($i = 1; $i <= 5; $i++)
                <span class="star">
                    {{ $i <= ($post->difficulty_id ?? 0) ? '★' : '☆' }}
                </span>
            @endfor
        </span>

        {{-- カテゴリ --}}
        @if ($post->categories->count() > 0)
            <div class="categories">
                @foreach ($post->categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="category">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 本文（画像＋コメント） --}}
    <div class="post-contents">

        @foreach ($post->contents as $content)
            <div class="post-content">

                {{-- 画像 --}}
                @if (!empty($content->image_path))
                    <img src="{{ asset('fileassets/'.$content->image_path) }}"
                         alt="{{ $post->title }}"
                         class="post-image">
                @endif

                {{-- コメント --}}
                @if (!empty($content->comment))
                    <p class="post-text">
                        {{ $content->comment }}
                    </p>
                @endif

            </div>
        @endforeach

    </div>

    {{-- 使用ツール --}}
    @if ($post->tools->count() > 0)
        <div class="post-tools">
            <h3>使用ツール</h3>
            <ul class="tool-list">
                @foreach ($post->tools as $tool)
                    <li class="tool-item">
                        {{ $tool->name }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- タグ --}}
    @if ($post->tags->count() > 0)
        <div class="post-tags">
            @foreach ($post->tags as $tag)
                <span class="tag">
                    #{{ $tag->name }}
                </span>
            @endforeach
        </div>
    @endif
 {{-- コメント欄 --}}
<div class="comments">

    {{-- 戻る・編集 --}}
    <div class="post-actions">

        <a href="{{ redirect()->back()->getTargetUrl() }}"
        class="btn-back">
            戻る
        </a>

        @auth
            @if (auth()->id() === $post->user_id)
                <a href="{{ route('users.posts.edit', $post) }}"
                class="btn-edit">
                    編集する
                </a>
            @endif
        @endauth

    </div>
    {{-- コメント欄 --}}
    <div class="comments">

        <h3>コメント</h3>

        @auth
            <form action="{{ route('comments.store', $post) }}" method="POST">
                @csrf
                <textarea
                name="body"
                rows="4"
                required
                class="comment-textarea"
                placeholder="コメントを入力してください"
                >
                </textarea>

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
        @endauth

    </div>


    </div>

</div>
@endsection
