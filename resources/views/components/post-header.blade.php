{{-- 投稿一覧ヘッダー＆フィルタ --}}
<section class="page-section">

    <header class="post-header">
        <h2 class="post-header__title">
            <a href="{{ route('public.posts.index') }}">DIY ラボ</a>
        </h2>

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

    @include('components.search-js')
</section>
