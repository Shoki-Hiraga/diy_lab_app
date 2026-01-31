    <div class="post-list">

        @forelse ($posts as $post)

        <article class="post-item">
            {{-- ユーザー向けオーバーレイ（通知用） 
            @include('components.common.users-post-overlay')
            --}}

            {{-- =========================
                 ▼ クリック可能なカード
                 ========================= --}}
            <div class="post-card">
                <a
                    href="{{ route('users.posts.show', $post) }}"
                    class="post-card-link"
                >

                    {{-- メイン画像 --}}
                    @php
                        $mainImage = optional($post->contents->first())->image_path;
                    @endphp

                    @if ($mainImage)
                        <img
                            src="{{ asset('fileassets/' . $mainImage) }}"
                            alt="{{ $post->title }}"
                            class="post-image"
                            onerror="this.onerror=null; this.outerHTML='<div class=&quot;post-image no-image&quot;>No Image</div>';"
                        >
                    @else
                        <div class="post-image no-image">
                            No Image
                        </div>
                    @endif

                    {{-- 本文 --}}
                    <div class="post-body">

                        {{-- 難易度・投稿日 --}}
                        <div class="post-meta">

                            <span class="difficulty">
                                難易度：
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star">
                                        {{ $i <= ($post->difficulty_id ?? 0) ? '★' : '☆' }}
                                    </span>
                                @endfor
                            </span>

                            <span class="date">
                                {{ $post->created_at->format('Y/m/d') }}
                            </span>

                        </div>

                        <h3 class="post-title">
                        <span class="post-title-text">
                            {{ $post->title }}
                        </span>
                        </h3>

                    </div>
                </a>
                <div class="post-meta-outside">
                    @include('components.comments.count', ['post' => $post])
                    @include('components.reactions.reaction')
                </div>
            </div>

            {{-- =========================
                 ▼ カード外メタ情報
                 ========================= --}}
            <div class="post-meta-outside">

                {{-- カテゴリ --}}
                <div class="categories">
                    <span class="category-label">カテゴリ</span>

                    @foreach ($post->categories->take(1) as $category)
                        <a
                            href="{{ route('categories.show', $category) }}"
                            class="category"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach

                    @if ($post->categories->count() > 3)
                        <span class="category-more">
                            +{{ $post->categories->count() - 3 }}
                        </span>
                    @endif
                </div>

                {{-- 投稿者 --}}
                <div class="post-author">
                    <a
                        href="{{ route('creators.show', $post->user) }}"
                        class="author-link"
                        title="投稿者：{{ $post->user->username }}"
                    >

                        {{-- アイコン --}}
                        <span class="author-icon-link author-icon-circle">
                            @if ($post->user->profile && $post->user->profile->profile_image_url)
                                <img
                                    src="{{ asset('fileassets/icons/' . $post->user->profile->profile_image_url) }}"
                                    alt="{{ $post->user->username }}"
                                    class="user-icon"
                                    onerror="this.onerror=null; this.outerHTML='<i class=&quot;fa-solid fa-user user-icon&quot;></i>';"
                                >
                            @else
                                <i class="fa-solid fa-user user-icon"></i>
                            @endif
                        </span>

                        <span class="author-name">
                            {{ \Illuminate\Support\Str::limit($post->user->username, 10, '…') }}
                        </span>

                    </a>
                </div>

                {{-- 編集ボタン --}}
                @auth
                    @if (auth()->id() === $post->user_id)
                        <div class="post-actions">
                            <a
                                href="{{ route('users.posts.edit', $post) }}"
                                class="btn-edit"
                            >
                                編集
                            </a>
                        </div>
                    @endif
                @endauth

            </div>

        </article>

        @empty
            <p class="no-posts">
                この投稿者の投稿はありません。
            </p>
        @endforelse

    </div>

    <div class="pagination-wrapper">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>

