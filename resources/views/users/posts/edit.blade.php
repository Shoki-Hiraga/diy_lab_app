@extends('layouts.app')

@section('content')
<div class="post-wrapper">
    <h2>投稿の編集</h2>

    {{-- ユーザー情報 --}}
    @php
        $iconPath = $user->profile && $user->profile->profile_image_url
            ? asset('assets/icons/'.$user->profile->profile_image_url)
            : asset('assets/images/default_icon.png');
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

        <div class="form-group">
            <label>カテゴリ</label>
            <div class="checkbox-group">
                @foreach($categories as $category)
                    <label>
                        <input type="checkbox"
                               name="category_id[]"
                               value="{{ $category->id }}"
                               {{ in_array($category->id, $checkedCategories) ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
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
            <div class="checkbox-group">
                @foreach($tools as $tool)
                    <label>
                        <input type="checkbox"
                               name="tools[]"
                               value="{{ $tool->id }}"
                               {{ in_array($tool->id, $checkedTools) ? 'checked' : '' }}>
                        {{ $tool->name }}
                    </label>
                @endforeach
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
