@extends('layouts.app')

@section('content')
<div class="post-wrapper">

    <h2>{{ $post->title }}</h2>

    {{-- 投稿者 --}}
    <div class="user-info">
        <img src="{{ asset('assets/icons/' . ($post->user->profile->profile_image_url ?? 'default_icon.png')) }}"
             class="user-icon">
        <span class="username">{{ $post->user->username }}</span>
    </div>

    {{-- 難易度 --}}
    <div class="difficulty">
        難易度：
        @for($i = 1; $i <= 5; $i++)
            <span class="star">{{ $i <= $post->difficulty_id ? '★' : '☆' }}</span>
        @endfor
    </div>

    {{-- カテゴリ --}}
    <div class="categories">
        @foreach($post->categories as $category)
            <span class="badge">{{ $category->name }}</span>
        @endforeach
    </div>

    {{-- 使用ツール --}}
    <div class="tools">
        @foreach($post->tools as $tool)
            <span class="badge">{{ $tool->name }}</span>
        @endforeach
    </div>

    {{-- 写真とコメント --}}
    @foreach($post->contents as $content)
        <div class="photo-comment-block">
            @if($content->image_path)
                <img src="{{ asset('assets/'.$content->image_path) }}" class="post-image">
            @endif
            @if($content->comment)
                <p>{{ $content->comment }}</p>
            @endif
        </div>
    @endforeach

</div>
@endsection
