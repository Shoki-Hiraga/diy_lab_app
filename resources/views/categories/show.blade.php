@extends('layouts.public')

@section('post-header')
    @include('components.common.post-header')

@section('title', $category->name . ' の投稿一覧')
@endsection

@section('content')

<section class="page-section">
    <div class="post-wrapper">
    <a href="{{ route('categories.index') }}" class="back-link">
        ← カテゴリ一覧へ戻る
    </a>

    <h2 class="page-title">
        {{ $category->name }} の投稿一覧
    </h2>
        @include('components.common.post-card')
    </div>

@endsection
