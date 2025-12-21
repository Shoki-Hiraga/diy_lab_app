@extends('layouts.public')

@section('title', 'DIY ラボ')
@section('description', 'DIYの投稿一覧ページです。人気のDIYや最新の投稿をチェックできます。')

@section('post-header')
    @include('components.post-header')
@endsection

@section('content')

<section class="page-section">

    @include('components.post-card')