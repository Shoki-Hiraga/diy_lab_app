@extends('layouts.public')

@section('title', 'カテゴリ一覧')
@section('ogp_title', 'カテゴリ一覧｜DIYラボ')
@section('ogp_description', 'DIY LABカテゴリ一覧ページです。')

@section('post-header')
    @include('components.common.post-header')
@endsection

@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
    ['label' => 'カテゴリ一覧', 'url' => null],
];
@endphp

{{-- ▼ パンくず構造化データ --}}
@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

{{-- ▼ 一覧構造化データ --}}
@include('components.seo.item-list-jsonld', [
    'title' => 'カテゴリ一覧',
    'description' => 'DIY LABカテゴリ一覧ページです。',
    'items' => $categories,
    'routeName' => 'categories.show',
])

{{-- ▼ 一覧UI --}}
@include('components.common.type-list', [
    'title' => 'カテゴリ一覧',
    'items' => $categories,
    'routeName' => 'categories.show',
    'countField' => 'published_posts_count',
])

@endsection
