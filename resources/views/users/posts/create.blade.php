@extends('layouts.app')

@section('content')
<div class="post-wrapper">
    <h2>投稿の作成</h2>

    {{-- 🔹 ユーザー情報（アイコン＋ユーザー名） --}}
    @php
        $iconPath = $user->profile && $user->profile->profile_image_url
            ? asset('storage/icons/'.$user->profile->profile_image_url)
            : asset('storage/images/default_icon.png');
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
                        <input type="file" name="images[]" id="image_0" accept="image/*" style="display:none;">
                        <label for="image_0" class="btn-upload">写真を追加</label>
                        <div class="preview"></div>
                    </div>
                    <textarea name="comments[]" placeholder="この写真の説明を入力..."></textarea>
                </div>
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

{{-- スクリプト --}}
<script>
/* ★ 難易度 星の選択 */
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

document.querySelectorAll('.toggle-btn').forEach(button => {
    button.addEventListener('click', () => {
        const targetId = button.getAttribute('data-target');
        const targetGroup = document.getElementById(targetId);

        const hiddenItems = targetGroup.querySelectorAll('.hidden');
        if (hiddenItems.length > 0) {
            // 開く
            hiddenItems.forEach(item => item.classList.remove('hidden'));
            targetGroup.classList.add('expanded');
            targetGroup.classList.remove('collapsed');
            button.classList.add('active');
            button.textContent = '閉じる';
        } else {
            // 閉じる
            const labels = targetGroup.querySelectorAll('label');
            labels.forEach((label, index) => {
                if (index >= 10) label.classList.add('hidden');
            });
            targetGroup.classList.add('collapsed');
            targetGroup.classList.remove('expanded');
            button.classList.remove('active');
            button.textContent = 'もっと見る';
        }
    });
});

/* ★ 画像プレビュー＋削除＋自動追加 */
function handleImagePreview(event, previewElement) {
    const file = event.target.files[0];
    if (!file) {
        previewElement.innerHTML = "";
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        previewElement.innerHTML = `
            <div class="preview-wrapper">
                <img src="${e.target.result}" class="preview-image" alt="preview">
                <button type="button" class="btn-remove">×</button>
            </div>
        `;
        previewElement.querySelector('.btn-remove').addEventListener('click', function() {
            const input = previewElement.closest('.image-upload').querySelector('input[type=file]');
            input.value = "";
            previewElement.innerHTML = "";
        });

        // ★ アップロード完了後に次のブロックを自動追加
        addNewPhotoBlock();
    };
    reader.readAsDataURL(file);
}

/* ★ 新しい写真ブロックを追加 */
function addNewPhotoBlock() {
    const container = document.getElementById('photo-comment-area');
    const count = container.children.length;
    const newId = 'image_' + count;

    const newBlock = document.createElement('div');
    newBlock.classList.add('photo-comment-block');
    newBlock.innerHTML = `
        <div class="image-upload">
            <input type="file" name="images[]" id="${newId}" accept="image/*" style="display:none;">
            <label for="${newId}" class="btn-upload">写真を追加</label>
            <div class="preview"></div>
        </div>
        <textarea name="comments[]" placeholder="この写真の説明を入力..."></textarea>
    `;

    container.appendChild(newBlock);

    const input = newBlock.querySelector('input[type=file]');
    const preview = newBlock.querySelector('.preview');
    input.addEventListener('change', e => handleImagePreview(e, preview));
}

/* ★ 初期のfile inputにイベント付与 */
document.querySelectorAll('input[type=file]').forEach(input => {
    const preview = input.closest('.image-upload').querySelector('.preview');
    input.addEventListener('change', e => handleImagePreview(e, preview));
});
</script>
@endsection
