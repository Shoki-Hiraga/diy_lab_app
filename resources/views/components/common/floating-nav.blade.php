@php
    $isActive = fn($name) => request()->routeIs($name) ? 'is-active' : '';
@endphp

<nav class="floating-nav">
    <div class="floating-nav__inner">
        <ul class="floating-nav__list">

            {{-- TOP --}}
            <li class="floating-nav__item">
                <a href="{{ route('public.posts.index') }}"
                class="floating-nav__link {{ $isActive('public.posts.*') }}">
                    <i class="fa-solid fa-house"></i>
                    <span>TOP</span>
                </a>
            </li>

            {{-- 検索 --}}
            <li class="floating-nav__item">
                <a href="{{ route('users.posts.index') }}"
                class="floating-nav__link {{ $isActive('users.posts.index') }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>検索</span>
                </a>
            </li>

            {{-- =========================
                ログイン中
            ========================= --}}
            @auth
                {{-- 中央＋ --}}
                <li class="floating-nav__item floating-nav__item--center">
                    <a href="{{ route('users.posts.create') }}"
                    class="floating-nav__link floating-nav__link--center {{ $isActive('users.posts.create') }}">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </li>

                {{-- お知らせ --}}
                <li class="floating-nav__item">
                    <a href="{{ route('users.top') }}"
                    class="floating-nav__link {{ $isActive('users.top') }}">
                        <i class="fa-regular fa-bell"></i>
                        <span>お知らせ</span>
                    </a>
                </li>

                {{-- マイページ --}}
                <li class="floating-nav__item">
                    <a href="{{ route('users.top') }}"
                    class="floating-nav__link {{ $isActive('users.profile.*') }}">
                        <i class="fa-regular fa-user"></i>
                        <span>マイページ</span>
                    </a>
                </li>
            @endauth

            {{-- =========================
                未ログイン
            ========================= --}}
            @guest
                {{-- 中央＋（ログイン誘導） --}}
                <li class="floating-nav__item floating-nav__item--center">
                    <a href="{{ route('login') }}"
                    class="floating-nav__link floating-nav__link--center">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </li>

                {{-- ログイン --}}
                <li class="floating-nav__item">
                    <a href="{{ route('login') }}"
                    class="floating-nav__link {{ $isActive('login') }}">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>ログイン</span>
                    </a>
                </li>

                {{-- 会員登録 --}}
                @if (Route::has('register'))
                    <li class="floating-nav__item">
                        <a href="{{ route('register') }}"
                        class="floating-nav__link {{ $isActive('register') }}">
                            <i class="fa-solid fa-user-plus"></i>
                            <span>会員登録</span>
                        </a>
                    </li>
                @endif
            @endguest
        </ul>
    </div>
</nav>
