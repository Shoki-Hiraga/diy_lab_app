<div class="post-header">
    <h2>DIY 投稿一覧</h2>

    <div class="header-actions">
        <a href="{{ route('categories.index') }}" class="btn-type-list">
            📂 カテゴリ一覧
        </a>

        <a href="{{ route('difficulties.index') }}" class="btn-type-list">
            ⭐ 難易度一覧
        </a>
    </div>
</div>

{{-- カテゴリナビ --}}
@if ($categories->count())
    <div class="category-nav">
        @foreach ($categories as $category)
            <a href="{{ route('categories.show', $category) }}"
               class="category-chip">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
@endif

{{-- 難易度ナビ --}}
@if ($difficulties->count())
    <div class="difficulty-nav">
        @foreach ($difficulties as $difficulty)
            <a href="{{ route('difficulties.show', $difficulty) }}"
               class="difficulty-chip">
                {{ str_repeat('★', $difficulty->id) }}
            </a>
        @endforeach
    </div>
@endif

<a href="{{ route('users.top') }}">
    ユーザーのウェルカムページへ
</a>
