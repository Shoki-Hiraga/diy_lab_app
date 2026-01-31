<div class="post-reactions">

    {{-- いいね --}}
    @auth
        <form method="POST"
              action="{{ route('posts.reaction', [$post, 'like']) }}"
              class="reaction-form"
              data-type="like">
            @csrf
            <button type="submit"
                    class="reaction-btn like
                    {{ $post->isReactedBy('like', auth()->id()) ? 'active' : '' }}">
                🔨 <span class="reaction-count">{{ $post->likes()->count() }}</span>
            </button>
        </form>
    @else
        <span>🔨 {{ $post->likes()->count() }}</span>
    @endauth


    {{-- ブックマーク --}}
    @auth
        <form method="POST"
              action="{{ route('posts.reaction', [$post, 'bookmark']) }}"
              class="reaction-form"
              data-type="bookmark">
            @csrf
            <button type="submit"
                    class="reaction-btn bookmark
                    {{ $post->isReactedBy('bookmark', auth()->id()) ? 'active' : '' }}">
                📌 <span class="reaction-count">{{ $post->bookmarks()->count() }}</span>
            </button>
        </form>
    @else
        <span>📌 {{ $post->bookmarks()->count() }}</span>
    @endauth

</div>
