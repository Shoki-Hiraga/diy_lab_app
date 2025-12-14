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
        <img src="{{ $iconPath }}" alt="ユーザー画像" class="user-icon">
        <span class="username">
            {{ $post->user->username }}
        </span>
        <span class="date">
            {{ $post->created_at->format('Y/m/d') }}
        </span>
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
                    <span class="category">
                        {{ $category->name }}
                    </span>
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
 
    {{-- 戻る --}}
    <div class="post-actions">
        <a href="{{ route('public.posts.index') }}"
           class="btn-back">
            一覧に戻る
        </a>
    </div>

</div>
@endsection
