@extends('layouts.app')

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.post-header')
@endsection

@section('content')
<div class="post-wrapper">
    <h2>投稿の編集</h2>

    {{-- ユーザー情報 --}}
    @php
        $iconPath = $user->profile && $user->profile->profile_image_url
            ? asset('fileassets/icons/'.$user->profile->profile_image_url)
            : asset('fileassets/images/default_icon.png');
    @endphp

    <div class="user-info">
        <img src="{{ $iconPath }}" class="user-icon">
        <span class="username">{{ $user->username }} さんの投稿</span>
    </div>

    {{-- エラーメッセージ --}}
    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>・{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 更新フォーム --}}
    <form action="{{ route('users.posts.update', $post->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="post-form">
        @csrf
        @method('PUT')

        {{-- ✅ status（初期値は現在の投稿状態 or old） --}}
        <input type="hidden"
               name="status"
               id="status"
               value="{{ old('status', $post->status) }}">

        {{-- タイトル --}}
        <div class="form-group">
            <label for="title">タイトル</label>
            <input type="text"
                   name="title"
                   id="title"
                   value="{{ old('title', $post->title) }}"
                   required>
        </div>

        {{-- 難易度 --}}
        <div class="form-group">
            <label>難易度</label>
            @php
                $difficulty = old('difficulty_id', $post->difficulty_id);
            @endphp
            <div class="stars" id="difficulty-stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="star {{ $difficulty >= $i ? 'selected' : '' }}"
                          data-value="{{ $i }}">
                        {{ $difficulty >= $i ? '★' : '☆' }}
                    </span>
                @endfor
            </div>
            <input type="hidden"
                   name="difficulty_id"
                   id="difficulty"
                   value="{{ $difficulty }}">
        </div>

        {{-- カテゴリ --}}
        @php
            $checkedCategories = old(
                'category_id',
                $post->categories->pluck('id')->toArray()
            );
        @endphp

        {{-- カテゴリ --}}
        @php
            $checkedCategories = old(
                'category_id',
                $post->categories->pluck('id')->toArray()
            );
        @endphp

        <div class="form-group">
            <label>カテゴリ一覧</label>

            <div class="checkbox-group collapsed" id="category-group">
                @foreach($categories as $category)
                    @php
                        $checked = in_array($category->id, $checkedCategories);

                        // ✅ 10個目以降 & 未選択 → hidden
                        $hiddenClass = ($loop->index >= 10 && !$checked)
                            ? 'hidden-category hidden'
                            : '';
                    @endphp

                    <label class="{{ $hiddenClass }}">
                        <input type="checkbox"
                            name="category_id[]"
                            value="{{ $category->id }}"
                            {{ $checked ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>

            @if(count($categories) > 10)
                <button type="button"
                        class="toggle-btn"
                        data-target="category-group">
                    他のカテゴリ ▼
                </button>
            @endif
        </div>

        {{-- ツール --}}
        @php
            $checkedTools = old(
                'tools',
                $post->tools->pluck('id')->toArray()
            );
        @endphp

        <div class="form-group">
            <label>使用ツール</label>

            <div class="checkbox-group collapsed" id="tool-group">
                @foreach($tools as $tool)
                    @php
                        $checked = in_array($tool->id, $checkedTools);

                        // ✅ 10個目以降 & 未選択 → hidden
                        $hiddenClass = ($loop->index >= 10 && !$checked)
                            ? 'hidden-tool hidden'
                            : '';
                    @endphp

                    <label class="{{ $hiddenClass }}">
                        <input type="checkbox"
                            name="tools[]"
                            value="{{ $tool->id }}"
                            {{ $checked ? 'checked' : '' }}>
                        {{ $tool->name }}
                    </label>
                @endforeach
            </div>

            @if(count($tools) > 10)
                <button type="button"
                        class="toggle-btn"
                        data-target="tool-group">
                    他のツール ▼
                </button>
            @endif
        </div>

          {{-- タグ --}}
        @php
            $tagString = old(
                'tags',
                $post->tags
                    ->pluck('name')
                    ->map(fn ($name) => "#{$name}")
                    ->implode(' ')
            );
        @endphp

        <div class="form-group">
            <label for="tags">タグ</label>

            <input type="text"
                name="tags"
                id="tags"
                value="{{ $tagString }}"
                placeholder="#DIY #木工 #初心者">
        </div>

        {{-- 写真＋コメント --}}
        <div class="form-group">
            <label>写真とコメント</label>

            <div id="photo-comment-area">

                {{-- 既存画像 --}}
                @foreach($post->contents->sortBy('order') as $index => $content)
                    <div class="photo-comment-block" data-existing="1">

                        <input type="hidden"
                            name="existing_contents[{{ $content->id }}][delete]"
                            value="0"
                            class="delete-flag">

                        <div class="image-upload">
                            <div class="preview">
                                <div class="preview-wrapper">
                                    <img src="{{ asset('fileassets/'.$content->image_path) }}"
                                        class="preview-image">

                                    {{-- ✅ ChatGPT風 × --}}
                                    <button type="button"
                                            class="btn-remove">
                                        ×
                                    </button>
                                </div>
                            </div>

                            <input type="file"
                                name="existing_contents[{{ $content->id }}][image]"
                                id="existing_image_{{ $content->id }}"
                                accept="image/*"
                                style="display:none;">

                            <label for="existing_image_{{ $content->id }}"
                                class="btn-upload">
                                写真を変更
                            </label>
                        </div>

                        <textarea name="existing_contents[{{ $content->id }}][comment]"
                                placeholder="この写真の説明を入力...">{{ $content->comment }}</textarea>
                    </div>
                @endforeach

                {{-- ✅ 新規追加用 --}}
                <div class="photo-comment-block">
                    <div class="image-upload">
                        <input type="file"
                            name="images[]"
                            id="image_new_0"
                            accept="image/*"
                            style="display:none;">

                        <label for="image_new_0" class="btn-upload">
                            写真を追加
                        </label>

                        <div class="preview"></div>
                    </div>
                    <textarea name="comments[]" placeholder="この写真の説明を入力..."></textarea>
                </div>
            </div>
        </div>

        {{-- ボタン --}}
        <div class="button-group">
            <button type="button"
                    class="btn-cancel"
                    onclick="history.back()">
                キャンセル
            </button>

            {{-- 下書き --}}
            <button type="submit"
                    class="btn-draft"
                    onclick="document.getElementById('status').value='{{ \App\Models\Post::STATUS_DRAFT }}'">
                下書き保存
            </button>

            {{-- 公開 --}}
            <button type="submit"
                    class="btn-submit"
                    onclick="document.getElementById('status').value='{{ \App\Models\Post::STATUS_PUBLISHED }}'">
                公開する
            </button>
        </div>
    </form>

    {{-- 削除フォーム --}}
    <form action="{{ route('users.posts.destroy', $post->id) }}"
          method="POST"
          onsubmit="return confirm('本当に削除しますか？');"
          style="margin-top:1.2rem;">
        @csrf
        @method('DELETE')

        <button type="submit"
                style="width:100%;background:#dc2626;color:#fff;border:none;border-radius:8px;padding:0.8rem;">
            削除する
        </button>
    </form>
</div>

@include('users.posts.partials.form-scripts')
@endsection
