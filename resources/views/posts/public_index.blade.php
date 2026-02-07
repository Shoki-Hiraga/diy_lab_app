@extends('layouts.public')

@section('title', 'DIY LAB')
@section('ogp_title', 'DIY LAB｜DIY投稿一覧')
@section('ogp_description', 'DIY初心者から上級者まで。人気のDIYや最新の投稿を一覧でチェックできます。')


@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
];
@endphp

{{-- ▼ パンくず --}}
@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

{{-- ▼ 投稿一覧 構造化データ --}}
@include('components.seo.post-list-jsonld', [
    'posts' => $posts,
])

<section class="page-section">
    <div class="post-wrapper">
        @include('components.common.post-card')
    </div>
</section>

@endsection
