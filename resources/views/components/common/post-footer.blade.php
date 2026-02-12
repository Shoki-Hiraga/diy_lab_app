
@include('components.common.cta-card')

<div class="post-footer">
    <div class="post-footer__inner">

        {{-- 上段：ロゴ + 認証 --}}
        <div class="post-footer__main">
            <h2 class="post-footer__title">
                <a href="{{ route('public.posts.index') }}">DIY LAB</a>
            </h2>

            <div class="footer-actions">
                <div class="footer-auth">
                    @guest
                        <a href="{{ route('login') }}" class="link-login">ログイン</a>
                        <a href="{{ route('register') }}" class="btn-register">会員登録</a>
                    @endguest
                </div>
            </div>
        </div>

        {{-- ナビ --}}
        <nav class="footer-nav">
            <a href="{{ route('public.posts.index') }}" class="nav-link">TOP</a>
            <a href="{{ route('categories.index') }}" class="nav-link">カテゴリ</a>
            <a href="{{ route('difficulties.index') }}" class="nav-link">難易度</a>
            <a href="{{ route('tags.index') }}" class="nav-link">タグ</a>
        </nav>

        <nav class="footer-nav footer-nav--legal">
            <a href="{{ route('legal.terms') }}" class="nav-link">利用規約</a>
            <a href="{{ route('legal.privacy') }}" class="nav-link">プライバシーポリシー</a>
        </nav>

    </div>
</div>
