@extends('layouts.app')

@section('content')
<div class="post-wrapper">
    <h2>投稿の作成</h2>

    {{-- ユーザー情報（アイコン＋ユーザー名） --}}
    @php
        $iconPath = $user->profile && $user->profile->profile_image_url
            ? asset('assets/icons/'.$user->profile->profile_image_url)
            : asset('assets/images/default_icon.png');
    @endphp

    <div class="user-info">
        <img src="{{ $iconPath }}" alt="ユーザー画像" class="user-icon">
        <span class="username">{{ $user->username }} さんの投稿</span>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>・{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.posts.store') }}" method="POST" enctype="multipart/form-data" class="post-form">
        @csrf

        {{-- タイトル --}}
        <div class="form-group">
            <label for="title">タイトル</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required>
        </div>

        {{-- 難易度 --}}
        <div class="form-group">
            <label>難易度</label>
            <div class="stars" id="difficulty-stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="star {{ old('difficulty_id') == $i ? 'selected' : '' }}" data-value="{{ $i }}">
                        {{ old('difficulty_id') >= $i ? '★' : '☆' }}
                    </span>
                @endfor
            </div>
            <input type="hidden" name="difficulty_id" id="difficulty" value="{{ old('difficulty_id', 0) }}">
        </div>

        {{-- カテゴリ --}}
        <div class="form-group">
            <label>カテゴリ一覧</label>
            <div class="checkbox-group collapsed" id="category-group">
                @foreach($categories as $category)
                    <label class="{{ $loop->index >= 10 ? 'hidden-category hidden' : '' }}">
                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                            {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
            @if(count($categories) > 10)
                <button type="button" class="toggle-btn" data-target="category-group">他のカテゴリ ▼</button>
            @endif
        </div>

        {{-- ツール --}}
        <div class="form-group">
            <label>使用ツール</label>
            <div class="checkbox-group collapsed" id="tool-group">
                @foreach($tools as $tool)
                    <label class="{{ $loop->index >= 10 ? 'hidden-tool hidden' : '' }}">
                        <input type="checkbox" name="tools[]" value="{{ $tool->id }}"
                            {{ in_array($tool->id, old('tools', [])) ? 'checked' : '' }}>
                        {{ $tool->name }}
                    </label>
                @endforeach
            </div>
            @if(count($tools) > 10)
                <button type="button" class="toggle-btn" data-target="tool-group">他のツール ▼</button>
            @endif
        </div>

        {{-- 写真＋コメント --}}
        <div class="form-group">
            <label>写真とコメント</label>

            <div id="photo-comment-area">
                <div class="photo-comment-block">
                    <div class="image-upload">
                        <input type="file"
                            name="images[]"
                            id="image_0"
                            accept="image/*"
                            style="display:none;">

                        <label for="image_0" class="btn-upload">
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
            <button type="button" class="btn-cancel" onclick="history.back()">キャンセル</button>

            {{-- status 初期値 --}}
            <input type="hidden" name="status" id="status"
               value="{{ \App\Models\Post::STATUS_DRAFT }}">

            <button type="submit"
                    class="btn-draft"
                    onclick="document.getElementById('status').value='draft'">
                下書き保存
            </button>

            <button type="submit"
                    class="btn-submit"
                    onclick="document.getElementById('status').value='published'">
                投稿する
            </button>
        </div>
    </form>
</div>

@include('users.posts.partials.form-scripts')

@endsection
