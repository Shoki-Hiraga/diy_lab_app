@extends('layouts.public')

@section('title', 'タグ一覧')

@section('post-header')
    @include('components.common.post-header')
@endsection

@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
    ['label' => 'タグ一覧', 'url' => null],
];
@endphp

{{-- ▼ パンくず構造化データ --}}
@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

{{-- ▼ 一覧構造化データ --}}
@include('components.seo.item-list-jsonld', [
    'title' => 'タグ一覧',
    'description' => 'DIY LABタグ一覧ページです。',
    'items' => $tags,
    'routeName' => 'tags.show',
])

{{-- ▼ 一覧UI --}}
@include('components.common.type-list', [
    'title' => 'タグ一覧',
    'items' => $tags,
    'routeName' => 'tags.show',
    'countField' => 'published_posts_count',
    'labelCallback' => fn ($tag) => '#' . $tag->name,
])

@endsection
