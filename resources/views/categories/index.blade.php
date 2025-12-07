@extends('layouts.public')

@section('title', 'カテゴリ一覧')

@section('content')
<div class="category-wrapper">
    <h2>カテゴリ一覧</h2>

    <ul class="category-list">
        @foreach ($categories as $category)
            <li class="category-item">
                <a href="{{ route('categories.show', $category) }}">
                    {{ $category->name }}
                    <span class="count">
                        {{ $category->published_posts_count }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
