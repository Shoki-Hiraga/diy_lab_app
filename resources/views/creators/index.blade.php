@extends('layouts.public')

@section('title', '投稿者一覧')

@section('post-header')
    @include('components.common.post-header')
@endsection

@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
    ['label' => '投稿者一覧', 'url' => null],
];
@endphp

{{-- ▼ パンくず構造化データ --}}
@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

{{-- ▼ 一覧構造化データ --}}
@include('components.seo.item-list-jsonld', [
    'title' => '投稿者一覧',
    'description' => 'DIYラボの投稿者一覧ページです。',
    'items' => $creators->map(function ($creator) {
        $creator->name = $creator->username;
        return $creator;
    }),
    'routeName' => 'creators.show',
])

{{-- ▼ 一覧UI --}}
@include('components.common.type-list', [
    'title' => '投稿者一覧',
    'items' => $creators,
    'routeName' => 'creators.show',
    'countField' => 'published_posts_count',
    'labelCallback' => fn ($creator) => $creator->username,
])

@endsection
