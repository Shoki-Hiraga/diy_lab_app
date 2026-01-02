{{-- 投稿一覧ヘッダー＆フィルタ --}}
<header class="post-header">
    <div class="post-header__inner">

        <h2 class="post-header__title">
            <a href="{{ route('public.posts.index') }}">DIY ラボ</a>
        </h2>

        <div class="header-actions">

            {{-- 検索 --}}
            <div class="header-action header-action--search">
                <form action="{{ route('search.index') }}"
                    method="GET"
                    class="header-search">

                    <input
                        type="text"
                        name="q"
                        placeholder="キーワード検索"
                        value="{{ request('q') }}"
                    >

                    <button type="submit" class="search-submit">
                        🔍
                    </button>
                </form>
            </div>

            {{-- ナビ --}}
            <div class="header-action header-action--nav">
                <a href="{{ route('categories.index') }}" class="btn-type-nav">📂 カテゴリ</a>
                <a href="{{ route('difficulties.index') }}" class="btn-type-nav">⭐ 難易度</a>
                <a href="{{ route('tags.index') }}" class="btn-type-nav">🏷️ タグ</a>
                <a href="{{ route('users.top') }}" class="btn-type-nav">🏠 HOME</a>
            </div>
        </div>

    @include('components.search-js')

</header>
