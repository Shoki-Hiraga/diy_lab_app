<div class="post-reactions">

    {{-- いいね --}}
    @auth
        <form method="POST"
            action="{{ route('posts.reaction', [$post, 'like']) }}"
            class="reaction-form">
            @csrf
            <button type="submit"
                class="reaction-btn like
                {{ $post->isReactedBy('like', auth()->id()) ? 'active' : '' }}">
                🔨 {{ $post->likes()->count() }}
            </button>
        </form>
    @else
        <span class="reaction-count">
            🔨 {{ $post->likes()->count() }}
        </span>
    @endauth


    {{-- ブックマーク --}}
    @auth
        <form method="POST"
            action="{{ route('posts.reaction', [$post, 'bookmark']) }}"
            class="reaction-form">
            @csrf
            <button type="submit"
                class="reaction-btn bookmark
                {{ $post->isReactedBy('bookmark', auth()->id()) ? 'active' : '' }}">
                📌 {{ $post->bookmarks()->count() }}
            </button>
        </form>
    @else
        <span class="reaction-count">
            📌 {{ $post->bookmarks()->count() }}
        </span>
    @endauth

</div>
