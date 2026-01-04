<div class="comments" data-post-id="{{ $post->id }}">

    <h3 class="comment-title">
        コメント（<span id="comment-count">{{ $post->comments_count }}</span>件）
    </h3>

    {{-- コメント一覧 --}}
    <div id="comment-list" class="comment-list">
        @foreach ($post->rootComments as $comment)
            @include('components.comments.item', ['comment' => $comment])
        @endforeach
    </div>

    {{-- コメント投稿 --}}
    @include('components.comments.form')
</div>
