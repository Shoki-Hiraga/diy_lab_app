@extends('layouts.public')

@section('title', '難易度一覧')

@section('content')
<div class="type-wrapper">
    <h2>難易度一覧</h2>

    <ul class="type-list">
        @foreach ($difficulties as $difficulty)
            <li class="type-item">
                <a href="{{ route('difficulties.show', $difficulty) }}">
                    {{ str_repeat('★', $difficulty->id) }}
                    <span class="type-count">
                        {{ $difficulty->published_posts_count }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
