@extends('layouts.public')

@section('title', '難易度一覧')

@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
    ['label' => '難易度一覧', 'url' => null],
];
@endphp

{{-- ▼ パンくず構造化データ --}}
@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

{{-- ▼ 一覧構造化データ --}}
@include('components.seo.item-list-jsonld', [
    'title' => '難易度一覧',
    'description' => 'DIY LAB難易度一覧ページです。',
    'items' => $difficulties->map(function ($difficulty) {
        $difficulty->name = '★' . $difficulty->id . '（星' . $difficulty->id . '）';
        return $difficulty;
    }),
    'routeName' => 'difficulties.show',
])

{{-- ▼ 一覧UI --}}
@include('components.common.type-list', [
    'title' => '難易度一覧',
    'items' => $difficulties,
    'routeName' => 'difficulties.show',
    'countField' => 'published_posts_count',
    'labelCallback' => fn ($difficulty) => str_repeat('★', $difficulty->id),
])

@endsection
