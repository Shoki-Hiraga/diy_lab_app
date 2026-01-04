@extends('layouts.app')

{{-- ▼ post-header --}}
@section('post-header')
    @include('components.common.post-header')
@endsection

@section('content')

<div class="post-wrapper">
    <h2>プロフィール</h2>

    @if(session('success'))
        <div class="alert alert-success" style="background:#d4edda;color:#155724;padding:0.8rem;border-radius:8px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('users.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 基本情報 --}}
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->username) }}">
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="text" name="email" id="email" value="{{ old('email', $user->email) }}">
        </div>

        {{-- プロフィール文 --}}
        <div class="form-group">
            <label for="profile">自己紹介</label>
            <textarea name="profile" id="profile" rows="3">{{ old('profile', $user->profile->profile ?? '') }}</textarea>
        </div>


        {{-- アイコン --}}
        <div class="form-group">
            <label for="icon">アイコン画像</label>

            {{-- ✅ 画像選択ボタン --}}
            <div class="image-upload">
                <label for="icon-file" class="btn-upload">画像を選択</label>
                <input type="file" name="icon" id="icon-file" accept="image/*">
            </div>

            {{-- ✅ デフォルト画像 or 登録済み画像 --}}
            @php
                $iconPath = $user->profile && $user->profile->profile_image_url
                    ? asset('fileassets/icons/'.$user->profile->profile_image_url)
                    : asset('static/images/default_icon.png');
            @endphp

            {{-- ✅ プレビュー表示 --}}
            <div class="preview" id="preview-area" style="display: block;">
                <img id="preview-image"
                     src="{{ $iconPath }}"
                     alt="アイコン画像"
                     style="width:120px;height:120px;border-radius:50%;object-fit:cover;">
            </div>
        </div>

        {{-- SNSリンク --}}
        @if ($platforms->isNotEmpty())
            <h3 style="margin-top:1.5rem;color:#333;">SNSリンク</h3>

            @foreach ($platforms as $platform)
                @php
                    $link = $user->socialLinks->firstWhere('social_platforms_id', $platform->id);
                @endphp
                <div class="form-group">
                    <label>{{ $platform->name }} のURL</label>
                    <input type="text" name="social_links[{{ $platform->id }}]"
                        value="{{ old("social_links.$platform->id", $link->url ?? '') }}"
                        placeholder="{{ $platform->name }} のプロフィールURLを入力">
                </div>
            @endforeach
        @endif

        {{-- ボタン --}}
        <div class="button-group">
            <a href="{{ url()->previous() }}" class="btn-cancel">キャンセル</a>
            <button type="submit" class="btn-submit">保存する</button>
        </div>
    </form>
</div>

{{-- ✅ 画像プレビュー処理 --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('icon-file');
    if (!fileInput) return;

    fileInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('preview-image');
                img.src = e.target.result;
                img.style.display = 'block';
                document.getElementById('preview-area').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

@endsection
