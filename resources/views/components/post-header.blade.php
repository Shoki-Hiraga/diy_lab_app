{{-- 投稿一覧ヘッダー＆フィルタ --}}
<section class="page-section">

    {{-- ヘッダー --}}
    <header class="post-header">
        <h2 class="post-header__title">DIY 投稿一覧</h2>

        <div class="header-actions">
            <a href="{{ route('categories.index') }}" class="btn-type-list">
                📂 カテゴリ
            </a>
            <a href="{{ route('difficulties.index') }}" class="btn-type-list">
                ⭐ 難易度
            </a>
            <a href="{{ route('tags.index') }}" class="btn-type-list">
                🏷️ タグ
            </a>
            <a href="{{ route('users.top') }}" class="btn-type-list">
                🏠 マイページ
            </a>
        </div>
    </header>

    {{-- カテゴリナビ --}}
    @if ($categories->count())
        <nav class="type-nav" aria-label="カテゴリ">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category) }}"
                   class="type-chip">
                    {{ $category->name }}
                </a>
            @endforeach
        </nav>
    @endif

    {{-- タグナビ --}}
    @if ($tags->count())
        <nav class="type-nav" aria-label="タグ">
            @foreach ($tags as $tag)
                <a href="{{ route('tags.show', $tag) }}"
                   class="type-chip">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </nav>
    @endif

    {{-- 難易度ナビ --}}
    @if ($difficulties->count())
        <nav class="type-nav" aria-label="難易度">
            @foreach ($difficulties as $difficulty)
                <a href="{{ route('difficulties.show', $difficulty) }}"
                   class="type-chip">
                    {{ str_repeat('★', $difficulty->id) }}
                </a>
            @endforeach
        </nav>
    @endif

</section>
