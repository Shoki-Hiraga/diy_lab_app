{{-- 投稿一覧ヘッダー＆フィルタ --}}
<section class="page-section">

    <header class="post-header">
        <h2 class="post-header__title">DIY 投稿一覧</h2>

        <div class="header-actions">

            {{-- 検索 --}}
            <div class="header-action">
                <form action="{{ route('search.index') }}"
                      method="GET"
                      class="header-search"
                      data-search>

                    <input
                        type="text"
                        name="q"
                        placeholder="キーワード検索"
                        value="{{ request('q') }}"
                    >

                    <button type="submit" class="search-submit">🔍</button>

                    <button type="button"
                            class="search-toggle"
                            aria-label="検索を開く">
                        🔍
                    </button>
                </form>
            </div>

            {{-- ナビボタン --}}
            <div class="header-action">
                <a href="{{ route('categories.index') }}" class="btn-type-list">📂 カテゴリ</a>
                <a href="{{ route('difficulties.index') }}" class="btn-type-list">⭐ 難易度</a>
                <a href="{{ route('tags.index') }}" class="btn-type-list">🏷️ タグ</a>
                <a href="{{ route('users.top') }}" class="btn-type-list">🏠 マイページ</a>
            </div>

        </div>
    </header>

    {{-- カテゴリ --}}
    @if ($categories->count())
        <nav class="type-nav">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="type-chip">
                    {{ $category->name }}
                </a>
            @endforeach
        </nav>
    @endif

    {{-- タグ --}}
    @if ($tags->count())
        <nav class="type-nav">
            @foreach ($tags as $tag)
                <a href="{{ route('tags.show', $tag) }}" class="type-chip">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </nav>
    @endif

    {{-- 難易度 --}}
    @if ($difficulties->count())
        <nav class="type-nav">
            @foreach ($difficulties as $difficulty)
                <a href="{{ route('difficulties.show', $difficulty) }}" class="type-chip">
                    {{ str_repeat('★', $difficulty->id) }}
                </a>
            @endforeach
        </nav>
    @endif

    @include('components.search-js')
</section>
