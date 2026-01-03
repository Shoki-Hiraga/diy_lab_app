@extends('layouts.public')

@section('post-header')
    @include('components.post-header')

@section('title', $user->username . ' の投稿一覧')

@section('content')
@endsection

<section class="page-section">
    <div class="post-wrapper">
        <a href="{{ route('creators.index') }}" class="back-link">
            ← 投稿者一覧へ戻る
        </a>

        <h2 class="page-title">
            {{ $user->username }} の投稿一覧
        </h2>

    @include('components.post-card')

    </div>

@endsection
