@extends('layouts.public')

@section('post-header')
    @include('components.post-header')

@section('title', '#' . $tag->name . ' の投稿一覧')
@endsection

@section('content')

<section class="page-section">

    <a href="{{ route('tags.index') }}" class="back-link">
        ← タグ一覧へ戻る
    </a>

    <h2 class="page-title">
        #{{ $tag->name }} の投稿一覧
    </h2>

    @include('components.post-card')