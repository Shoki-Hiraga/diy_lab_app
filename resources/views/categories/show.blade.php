@extends('layouts.public')

@section('title', $category->name . ' の投稿一覧｜DIY LAB')
@section('ogp_title', $category->name . ' の投稿一覧｜DIY LAB')
@section(
    'ogp_description',
    $category->name . ' に属するDIY投稿一覧ページです。'
)


@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
    ['label' => 'カテゴリ一覧', 'url' => route('categories.index')],
    ['label' => $category->name, 'url' => null],
];
@endphp

{{-- ▼ パンくず --}}
@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

{{-- ▼ 投稿一覧 構造化データ --}}
@include('components.seo.post-list-jsonld', ['posts' => $posts])

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
</section>

@endsection
