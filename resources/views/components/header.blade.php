<div class="post-header">
    <h2>DIY 投稿一覧</h2>

    <div class="header-actions">
        <a href="{{ route('categories.index') }}" class="btn-category">
            📂 カテゴリ一覧
        </a>
    </div>
</div>

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


<a href="{{ route('users.top') }}">
    ユーザーのウェルカムページへ
</a>
