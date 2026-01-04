@extends('layouts.public')

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.common.post-header')
@endsection

@section('title', 'カテゴリ一覧')

@section('content')
<div class="type-wrapper">
    <h2>カテゴリ一覧</h2>

    <ul class="type-list">
        @foreach ($categories as $category)
            <li class="type-item">
                <a href="{{ route('categories.show', $category) }}">
                    {{ $category->name }}
                    <span class="type-count">
                        {{ $category->published_posts_count }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
