<div class="post-header">
    <h2>DIY 投稿一覧</h2>

    <div class="header-actions">
        <a href="{{ route('categories.index') }}" class="btn-type-list">
            📂 カテゴリ一覧
        </a>

        <a href="{{ route('difficulties.index') }}" class="btn-type-list">
            ⭐ 難易度一覧
        </a>

        <a href="{{ route('tags.index') }}" class="btn-type-list">
            🏷️ タグ一覧
        </a>    </div>
</div>

{{-- カテゴリナビ --}}
@if ($categories->count())
    <div class="type-nav">
        @foreach ($categories as $category)
            <a href="{{ route('categories.show', $category) }}"
               class="type-chip">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
@endif

{{-- タグナビ --}}
@if ($tags->count())
    <div class="type-nav">
        @foreach ($tags as $tag)
            <a href="{{ route('tags.show', $tag) }}"
               class="type-chip">
                #{{ $tag->name }}
            </a>
        @endforeach
    </div>
@endif

{{-- 難易度ナビ --}}
@if ($difficulties->count())
    <div class="type-nav">
        @foreach ($difficulties as $difficulty)
            <a href="{{ route('difficulties.show', $difficulty) }}"
               class="type-chip">
                {{ str_repeat('★', $difficulty->id) }}
            </a>
        @endforeach
    </div>
@endif

<a href="{{ route('users.top') }}">
    ユーザーのウェルカムページへ
</a>
