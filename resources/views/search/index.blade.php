@extends('layouts.public')

@section('title', '検索結果')
<meta name="robots" content="noindex">

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

@section('content')

<section class="page-section">

    <h2 class="page-title">検索</h2>

    {{-- =========================
         ▼ 検索条件チップ
         ========================= --}}
    @if(request()->filled('q') || request()->filled('difficulty_id'))
        <div class="search-chips">

            @if(request('q'))
                <a
                    href="{{ route('search.index', request()->except('q')) }}"
                    class="search-chip"
                >
                    「{{ request('q') }}」
                    <span class="chip-close">×</span>
                </a>
            @endif

            @if(request('difficulty_id'))
                <a
                    href="{{ route('search.index', request()->except('difficulty_id')) }}"
                    class="search-chip"
                >
                    難易度 {{ str_repeat('★', request('difficulty_id')) }}
                    <span class="chip-close">×</span>
                </a>
            @endif

        </div>
    @endif

    {{-- =========================
         ▼ 検索フォーム
         ========================= --}}
    <form method="GET" action="{{ route('search.index') }}" class="search-form">

        <div class="search-row">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="キーワード検索"
            >
        </div>

        <div class="search-row">
            <select name="difficulty_id">
                <option value="">難易度を選択</option>
                @foreach ($difficulties as $difficulty)
                    <option
                        value="{{ $difficulty->id }}"
                        @selected(request('difficulty_id') == $difficulty->id)
                    >
                        {{ str_repeat('★', $difficulty->id) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit">検索</button>
    </form>

    {{-- =========================
         ▼ 検索結果一覧
         ========================= --}}
    @include('components.post-card')