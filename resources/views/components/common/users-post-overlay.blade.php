@auth
    @php
        $route = request()->route()->getName();
    @endphp

    {{-- 👍 いいねされた投稿 --}}
    @if ($route === 'users.others.likes')
        <div class="post-overlay-badge like">
            👍 いいねされました
        </div>
    @endif

    {{-- 💬 コメントされた投稿 --}}
    @if ($route === 'users.others.comments')
        <div class="post-overlay-badge comment">
            💬 コメントがあります
        </div>
    @endif
@endauth
