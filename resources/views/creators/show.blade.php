@extends('layouts.public')

@section('post-header')
    @include('components.common.post-header')
@endsection

@section('title', $user->username . ' の投稿一覧')

@section('content')

<section class="page-section">
    <div class="post-wrapper">

        <a href="{{ route('creators.index') }}" class="back-link">
            ← 投稿者一覧へ戻る
        </a>

        <h2 class="page-title">
            {{ $user->username }} の投稿一覧
        </h2>

        {{-- プロフィール --}}
        <div class="creator-profile">
            @if($user->profile->profile_image_url)
                <img
                    src="{{ asset('fileassets/icons/' . $user->profile->profile_image_url) }}"
                    class="creator-profile-image"
                    alt="{{ $user->username }}"
                >
            @endif

            @if($user->profile->profile)
                <p class="creator-profile-text">
                    {{ $user->profile->profile }}
                </p>
            @endif
        </div>

        {{-- SNS --}}
        <ul class="creator-social-links">
            @forelse($user->socialLinks as $link)
                <li>
                    <span class="social-name">
                        {{ $link->platform->name }}
                    </span>
                    :
                    <a href="{{ $link->url }}" target="_blank" rel="noopener">
                        {{ $link->url }}
                    </a>
                </li>
            @empty
            @endforelse
        </ul>

        {{-- 投稿一覧 --}}
        @include('components.common.post-card')

    </div>
</section>

@endsection
