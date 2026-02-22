@extends('layouts.app')

@section('content')
<div class="post-wrapper">

    <h2>投稿一覧</h2>

    <form method="GET" action="{{ route('users.posts.index') }}" class="post-search">

        {{-- 条件 --}}
        <div class="post-search-row">
            <div class="post-search-item wide">
                <input type="text" name="keyword" class="post-search-input"
                    placeholder="タイトル・本文で検索" value="{{ request('keyword') }}">
            </div>

            <div class="post-search-item">
                <select name="status" class="post-search-select">
                    <option value="">ステータス</option>
                    <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>下書き</option>
                    <option value="published" {{ request('status')=='published' ? 'selected' : '' }}>公開</option>
                </select>
            </div>

            <div class="post-search-item">
                <select name="difficulty_id" class="post-search-select">
                    <option value="">難易度</option>
                    @foreach($difficulties as $difficulty)
                        <option value="{{ $difficulty->id }}">
                            {{ str_repeat('★', $difficulty->level) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="post-search-item">
                <select name="tag_id" class="post-search-select">
                    <option value="">タグ</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="post-search-item">
                <select name="category_id" class="post-search-select">
                    <option value="">カテゴリ</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ボタン --}}
        <div class="post-search-actions">
            <button type="submit" class="post-search-btn primary">
                検索する
            </button>
            <a href="{{ route('users.posts.index') }}" class="post-search-btn reset">
                リセット
            </a>
        </div>

    </form>


    {{-- ユーザー情報 --}}
    @php
        $defaultIcon = asset('static/images/default_icon.webp');

        $iconPath = $user->profile && $user->profile->profile_image_url
            ? asset('fileassets/icons/'.$user->profile->profile_image_url)
            : $defaultIcon;
    @endphp

    <div class="post-user-info">
        <img
            src="{{ $iconPath }}"
            alt="ユーザー画像"
            class="post-user-icon"
            onerror="this.onerror=null; this.src='{{ $defaultIcon }}';"
        >
        <span class="post-username">
            {{ $user->username }} さんの投稿一覧
        </span>
    </div>


    {{-- ▼ 共通カード --}}
    @include('components.common.post-card', ['posts' => $posts])

    <div class="pagination-wrapper">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
