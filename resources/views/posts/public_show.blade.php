@extends('layouts.public')

@section('title', $post->title . '｜DIY投稿')

@section('description', Str::limit(
    optional($post->contents->first())->comment ?? '',
    120
))

@section('ogp_title', $post->title . '｜DIY LAB')
@section(
    'ogp_description',
    Str::limit(optional($post->contents->first())->comment ?? '', 120)
)

@php
$ogpImage = optional($post->contents->first())->image_path
    ? asset('fileassets/'.$post->contents->first()->image_path)
    : asset('images/ogp/default.png');
@endphp

@section('ogp_image', $ogpImage)

@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
    ['label' => $post->title, 'url' => null],
];
@endphp

@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

<div class="post-wrapper">

    {{-- =====================
        投稿情報
    ===================== --}}
    <h2 class="post-title">{{ $post->title }}</h2>

    @php
        $iconPath = $post->user->profile && $post->user->profile->profile_image_url
            ? asset('fileassets/icons/'.$post->user->profile->profile_image_url)
            : asset('fileassets/images/default_icon.webp');
    @endphp

    <div class="user-info">
        <a href="{{ route('creators.show', $post->user) }}" class="user-icon-link">
            <img src="{{ $iconPath }}"
                alt="{{ $post->user->username }}"
                class="user-icon"
                onerror="this.onerror=null; this.src='{{ asset('static/images/default_icon.webp') }}';"
            >
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
                    投稿の編集
                </a>
            @endif
        @endauth
    </div>

    {{-- =====================
        コメント（AJAX）
    ===================== --}}
    @include('components.comments.index')
    </div>

{{-- コメント用JS --}}
@push('scripts')
    @vite(['resources/js/comments.js'])
@endpush

</div>
@endsection

