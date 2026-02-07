@extends('layouts.public')

@section(
    'title',
    '難易度 ' . str_repeat('★', $difficulty->id) . ' の投稿一覧｜DIY LAB'
)

@section(
    'ogp_title',
    '難易度 ' . str_repeat('★', $difficulty->id) . ' の投稿一覧｜DIY LAB'
)

@section(
    'ogp_description',
    '難易度 ' . $difficulty->id . '（' . str_repeat('★', $difficulty->id) . '）のDIY投稿一覧ページです。'
)

@section('post-header')
    @include('components.common.post-header')
@endsection

@section('content')

@php
$breadcrumbs = [
    ['label' => 'ホーム', 'url' => route('public.posts.index')],
    ['label' => '難易度一覧', 'url' => route('difficulties.index')],
    ['label' => '★' . $difficulty->id, 'url' => null],
];
@endphp

{{-- ▼ パンくず --}}
@include('components.seo.breadcrumbs-jsonld', ['breadcrumbs' => $breadcrumbs])

{{-- ▼ 投稿一覧 構造化データ --}}
@include('components.seo.post-list-jsonld', ['posts' => $posts])

<section class="page-section">
    <div class="post-wrapper">

        <h2 class="page-title">
            難易度
            <span class="stars">
                {{ str_repeat('★', $difficulty->id) }}
            </span>
            の投稿一覧
        </h2>

        @include('components.common.post-card')

    </div>
</section>

@endsection
