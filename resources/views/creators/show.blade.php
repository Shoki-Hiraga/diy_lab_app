@extends('layouts.public')

@section('title', $user->username . ' の投稿一覧')

@section('post-header')
    @include('components.post-header')
@endsection

@section('content')

<section class="page-section">

    <a href="{{ route('creators.index') }}" class="back-link">
        ← 投稿者一覧へ戻る
    </a>

    <h2 class="page-title">
        {{ $user->username }} の投稿一覧
    </h2>

    @include('components.post-card')