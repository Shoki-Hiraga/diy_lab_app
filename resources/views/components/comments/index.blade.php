<div class="comments" data-post-id="{{ $post->id }}">

    @include('components.comments.count', ['post' => $post])

    {{-- コメント一覧 --}}
    <div id="comment-list" class="comment-list">
        @foreach ($post->rootComments as $comment)
            @include('components.comments.item', ['comment' => $comment])
        @endforeach
    </div>

    {{-- コメント投稿 --}}
    @include('components.comments.form')
</div>
