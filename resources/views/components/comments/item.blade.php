<div class="comment-item {{ isset($isReply) && $isReply ? 'is-reply' : '' }}"
     data-id="{{ $comment->id }}">

    {{-- ヘッダー --}}
    <div class="comment-header">
        <span class="comment-user">{{ $comment->user->username }}</span>
        <span class="comment-date"
              data-time="{{ $comment->created_at->toISOString() }}">
            {{ $comment->created_at->format('Y/m/d H:i') }}
        </span>
    </div>

    {{-- 本文 --}}
    <div class="comment-body">
        {{ $comment->body }}
    </div>

    {{-- 操作 --}}
    @auth
        <div class="comment-actions">
            @if ($comment->user_id === auth()->id())
                <button type="button" class="comment-edit-btn">編集</button>
                <button type="button" class="comment-delete-btn">削除</button>
            @endif
            <button type="button" class="comment-reply-btn">返信</button>
        </div>
    @endauth

    {{-- 返信トグル --}}
    @php
        $replyCount = $comment->replies?->count() ?? 0;
    @endphp

    @if ($replyCount > 0)
        <button type="button"
                class="comment-replies-toggle"
                data-target="replies-{{ $comment->id }}">
            {{ $replyCount }}件の返信
        </button>
    @endif

    {{-- 返信一覧 --}}
    <div class="comment-replies"
         id="replies-{{ $comment->id }}"
         hidden>
        @foreach ($comment->replies ?? [] as $reply)
            @include('components.comments.item', [
                'comment' => $reply,
                'isReply' => true
            ])
        @endforeach
    </div>

</div>
