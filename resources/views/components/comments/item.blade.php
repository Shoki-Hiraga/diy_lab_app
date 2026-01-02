<div class="comment-item" data-id="{{ $comment->id }}">

    <div class="comment-header">
        <span class="comment-user">{{ $comment->user->username }}</span>
        <span class="comment-date">{{ $comment->created_at->format('Y/m/d H:i') }}</span>
    </div>

    <div class="comment-body">
        <p class="comment-text">{{ $comment->body }}</p>
    </div>

    @auth
        @if ($comment->user_id === auth()->id())
            <div class="comment-actions">
                <button class="comment-edit-btn">編集</button>
                <button class="comment-delete-btn">削除</button>
            </div>
        @endif
    @endauth

</div>
