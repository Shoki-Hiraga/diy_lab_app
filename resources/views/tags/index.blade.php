@extends('layouts.public')

@section('title', 'タグ一覧')

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

@section('content')
<div class="type-wrapper">
    <h2>タグ一覧</h2>

    <ul class="type-list">
        @foreach ($tags as $tag)
            <li class="type-item">
                <a href="{{ route('tags.show', $tag) }}">
                    #{{ $tag->name }}
                    <span class="type-count">
                        {{ $tag->published_posts_count }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
