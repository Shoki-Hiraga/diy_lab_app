
<article class="post-card">
    <a href="{{ route('users.posts.show', $post) }}">

        {{-- 画像 --}}
        @if($post->main_image_path)
            <img
                src="{{ asset('storage/' . $post->main_image_path) }}"
                alt=""
                class="post-image"
            >
        @else
            <div class="post-image no-image">
                No Image
            </div>
        @endif

        <div class="post-body">

            <div class="post-meta">
                <span class="difficulty">
                    <span class="stars">
                        {{ str_repeat('★', $post->difficulty_id) }}
                    </span>
                </span>
            </div>

            <h3 class="post-title">
                {{ $post->title }}
            </h3>

        </div>
    </a>
</article>

