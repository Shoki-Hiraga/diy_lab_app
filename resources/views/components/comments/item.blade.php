<div class="comment-item {{ isset($isReply) && $isReply ? 'is-reply' : '' }}"
     data-id="{{ $comment->id }}">

    <div class="comment-header">
        <span class="comment-user">{{ $comment->user->username }}</span>
        <span class="comment-date"
            data-time="{{ $comment->created_at->toISOString() }}">
            {{ $comment->created_at->format('Y/m/d H:i') }}
        </span>

    </div>

    <div class="comment-body">
        {{ $comment->body }}
    </div>

    @auth
        <div class="comment-actions">
            @if ($comment->user_id === auth()->id())
                <button class="comment-edit-btn">編集</button>
                <button class="comment-delete-btn">削除</button>
            @endif
            <button class="comment-reply-btn">返信</button>
        </div>
    @endauth

    {{-- 返信一覧 --}}
    <div class="comment-replies">
        @foreach ($comment->replies ?? [] as $reply)
            @include('components.comments.item', [
                'comment' => $reply,
                'isReply' => true
            ])
        @endforeach
    </div>

</div>
