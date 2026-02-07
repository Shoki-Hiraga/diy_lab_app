@extends('layouts.public')

@section('title', '#' . $tag->name . ' の投稿一覧｜DIY LAB')
@section('ogp_title', '#' . $tag->name . ' の投稿一覧｜DIY LAB')
@section(
    'ogp_description',
    '#' . $tag->name . ' が付いたDIY投稿一覧ページです。'
)

@section('post-header')
    @include('components.common.post-header')
@endsection

@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
    ['label' => 'タグ一覧', 'url' => route('tags.index')],
    ['label' => '#' . $tag->name, 'url' => null],
];
@endphp

{{-- ▼ パンくず --}}
@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

{{-- ▼ 投稿一覧 構造化データ --}}
@include('components.seo.post-list-jsonld', ['posts' => $posts])

<section class="page-section">
    <div class="post-wrapper">

        <a href="{{ route('tags.index') }}" class="back-link">
            ← タグ一覧へ戻る
        </a>

        <h2 class="page-title">
            #{{ $tag->name }} の投稿一覧
        </h2>

        @include('components.common.post-card')

    </div>
</section>

@endsection
