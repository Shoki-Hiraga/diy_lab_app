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

    {{-- ▼ 共通カード --}}
    @include('components.common.post-card', ['posts' => $posts])

    <div class="pagination-wrapper">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
