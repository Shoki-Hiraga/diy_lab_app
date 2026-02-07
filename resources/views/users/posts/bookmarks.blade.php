@extends('layouts.app')

@section('content')
<div class="post-wrapper">
    <h2>ブックマークした投稿</h2>

    @if ($posts->count())
        <div class="post-list">
            @include('components.common.post-card')
        </div>

        <div class="pagination-wrapper">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    @else
        <p class="no-posts">まだブックマークした投稿はありません。</p>
    @endif
</div>
@endsection
