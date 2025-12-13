@extends('layouts.public')

@section('title', '投稿者一覧')

@section('content')
<div class="type-wrapper">
    <h2>投稿者一覧</h2>

    <ul class="type-list">
        @foreach ($creators as $creator)
            <li class="type-item">
                <a href="{{ route('creators.show', $creator) }}">
                    {{ $creator->username }}
                    <span class="type-count">
                        {{ $creator->published_posts_count }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
