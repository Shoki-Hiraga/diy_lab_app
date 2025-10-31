@extends('layouts.app')

@section('content')
<div class="post-wrapper">
    <h2>投稿の作成</h2>

    <div class="user-info">
        <img src="{{ Auth::user()->profile_image ?? '/images/default_user.png' }}" alt="ユーザー画像" class="user-icon">
        <span class="username">{{ Auth::user()->name }} さんの投稿</span>
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

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="post-form">
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
                    <span class="star" data-value="{{ $i }}">☆</span>
                @endfor
            </div>
            <input type="hidden" name="difficulty_id" id="difficulty" value="{{ old('difficulty_id', 0) }}">
        </div>

        {{-- カテゴリ --}}
        <div class="form-group">
            <label>カテゴリ一覧</label>
            <div class="checkbox-group">
                @foreach($categories as $category)
                    <label>
                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 使用ツール --}}
        <div class="form-group">
            <label>使用ツール</label>
            <div class="checkbox-group">
                @foreach($tools as $tool)
                    <label>
                        <input type="checkbox" name="tools[]" value="{{ $tool->id }}">
                        {{ $tool->name }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- タグ --}}
        <div class="form-group">
            <label for="tags">タグ</label>
            <input type="text" name="tags" id="tags" placeholder="例: 木工, 溶接, ペイント" value="{{ old('tags') }}">
        </div>

        {{-- 本文 --}}
        <div class="form-group">
            <label for="body">本文</label>
            <textarea name="body" id="body" rows="6" required>{{ old('body') }}</textarea>
        </div>

        {{-- 画像 --}}
        <div class="form-group">
            <div class="image-upload">
                <input type="file" name="image" id="image" accept="image/*">
                <label for="image" class="btn-upload">写真を追加</label>
            </div>
        </div>

        {{-- ボタン --}}
        <div class="button-group">
            <button type="button" class="btn-cancel" onclick="history.back()">キャンセル</button>
            <button type="submit" name="draft" value="1" class="btn-draft">下書き保存</button>
            <button type="submit" class="btn-submit">投稿する</button>
        </div>
    </form>
</div>

{{-- 難易度 星選択スクリプト --}}
<script>
    const stars = document.querySelectorAll('.star');
    const difficultyInput = document.getElementById('difficulty');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = star.getAttribute('data-value');
            difficultyInput.value = value;

            stars.forEach(s => {
                s.textContent = s.getAttribute('data-value') <= value ? '★' : '☆';
            });
        });
    });
</script>
@endsection
