<div class="comments" data-post-id="{{ $post->id }}">

    <h3 class="comment-title">
        コメント（<span id="comment-count">{{ $post->comments_count }}</span>件）
    </h3>

    <div id="comment-list">
        @foreach ($post->rootComments as $comment)
            @include('components.comments.item', ['comment' => $comment])
        @endforeach
    </div>

    @include('components.comments.form')
</div>
