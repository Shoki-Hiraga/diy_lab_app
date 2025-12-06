
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