@extends('layouts.public')

@section('title', $user->username . ' の投稿一覧｜DIY LAB')
@section('ogp_title', $user->username . ' の投稿一覧｜DIY LAB')
@section(
    'ogp_description',
    $user->profile->profile
        ? Str::limit($user->profile->profile, 120)
        : $user->username . ' のDIY投稿一覧ページです。'
)

@php
$ogpImage = $user->profile && $user->profile->profile_image_url
    ? asset('fileassets/icons/' . $user->profile->profile_image_url)
    : asset('images/ogp/default.png');
@endphp
@section('ogp_image', $ogpImage)

@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
    ['label' => '投稿者一覧', 'url' => route('creators.index')],
    ['label' => $user->username, 'url' => null],
];
@endphp

{{-- ▼ パンくず --}}
@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

{{-- ▼ 投稿一覧 構造化データ --}}
@include('components.seo.post-list-jsonld', ['posts' => $posts])

<section class="page-section">
    <div class="post-wrapper">

        <h2 class="page-title">
            {{ $user->username }} の投稿一覧
        </h2>

        {{-- プロフィール --}}
        <div class="creator-profile">
            @if($user->profile?->profile_image_url)
                <img
                    src="{{ asset('fileassets/icons/' . $user->profile->profile_image_url) }}"
                    class="creator-profile-image"
                    alt="{{ $user->username }}"
                >
            @endif

            @if($user->profile?->profile)
                <p class="creator-profile-text">
                    {{ $user->profile->profile }}
                </p>
            @endif
        </div>

        {{-- SNS --}}
        <ul class="creator-social-links">
            @foreach($user->socialLinks as $link)
                <li>
                    <span class="social-name">{{ $link->platform->name }}</span>：
                    <a href="{{ $link->url }}" target="_blank" rel="noopener">
                        {{ $link->url }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- 投稿一覧 --}}
        @include('components.common.post-card')

    </div>
</section>

@endsection
