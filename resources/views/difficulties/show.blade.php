@extends('layouts.public')

@section(
    'title',
    '難易度 ' . str_repeat('★', $difficulty->id) . ' の投稿一覧'
)

@section('post-header')
    @include('components.post-header')
@endsection

@section('content')

<section class="page-section">

    <a href="{{ route('difficulties.index') }}" class="back-link">
        ← 難易度一覧へ戻る
    </a>

    <h2 class="page-title">
        難易度
        <span class="stars">
            {{ str_repeat('★', $difficulty->id) }}
        </span>
        の投稿一覧
    </h2>

    @include('components.post-card')