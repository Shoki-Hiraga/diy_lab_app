{{-- 投稿一覧ヘッダー中身 --}}
<div class="post-header">
    <div class="post-header__inner">
        
        {{-- メインエリア --}}
        <div class="post-header__main">
            <h2 class="post-header__title">
                <a href="{{ route('public.posts.index') }}">DIY LAB</a>
            </h2>

            {{-- 検索窓 --}}
            <div class="header-search-container" id="search-container">
                <form action="{{ route('search.index') }}" method="GET" class="header-search-form">
                    <input
                        type="text"
                        name="q"
                        placeholder="キーワード検索"
                        value="{{ request('q') }}"
                        autocomplete="off"
                    >
                    <button type="submit" class="search-inner-submit">🔍</button>
                </form>
            </div>

            <div class="header-actions">
                <button type="button" class="search-toggle" aria-label="検索を開く">
                    🔍
                </button>

                <div class="header-auth">
                    @guest
                        <a href="{{ route('login') }}" class="link-login">ログイン</a>
                        <a href="{{ route('register') }}" class="btn-register">会員登録</a>
                    @endguest
                </div>
            </div>
        </div>

        {{-- ナビ --}}
        <nav class="header-nav">
            <a href="{{ route('public.posts.index') }}" class="nav-link">TOP</a>
            <a href="{{ route('categories.index') }}" class="nav-link">カテゴリ</a>
            <a href="{{ route('difficulties.index') }}" class="nav-link">難易度</a>
            <a href="{{ route('tags.index') }}" class="nav-link">タグ</a>
        </nav>

    </div>

    @include('components.common.search-js')
</div>
