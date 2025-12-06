<script>
/* =========================
 * 星評価
 * ========================= */
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', () => {
        const value = star.dataset.value;
        document.getElementById('difficulty').value = value;

        document.querySelectorAll('.star').forEach(s => {
            s.textContent = s.dataset.value <= value ? '★' : '☆';
        });
    });
});

/* =========================
 * 画像プレビュー
 * ========================= */
function bindImageInput(input) {
    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;

        const preview = input.closest('.image-upload').querySelector('.preview');

        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `
                <div class="preview-wrapper">
                    <img src="${e.target.result}" class="preview-image">
                    <button type="button" class="btn-remove">×</button>
                </div>
            `;
        };
        reader.readAsDataURL(file);

        // ✅ 自分が「最後の input」なら追加
        const allInputs = [...document.querySelectorAll('#photo-comment-area input[type=file]')];
        if (allInputs[allInputs.length - 1] === input) {
            addNewBlock();
        }
    });
}

document.querySelectorAll('#photo-comment-area input[type=file]').forEach(bindImageInput);

/* =========================
 * 新規ブロック
 * ========================= */
function addNewBlock() {
    const area = document.getElementById('photo-comment-area');
    const index = area.children.length;

    const block = document.createElement('div');
    block.className = 'photo-comment-block';

    block.innerHTML = `
        <div class="image-upload">
            <input type="file"
                   name="images[]"
                   id="image_${index}"
                   accept="image/*"
                   style="display:none;">
            <label for="image_${index}" class="btn-upload">写真を追加</label>
            <div class="preview"></div>
        </div>
        <textarea name="comments[]" placeholder="この写真の説明を入力..."></textarea>
    `;

    area.appendChild(block);
    bindImageInput(block.querySelector('input[type=file]'));
}

/* =========================
 * × 削除
 * ========================= */
document.addEventListener('click', e => {
    if (!e.target.classList.contains('btn-remove')) return;

    const block = e.target.closest('.photo-comment-block');
    if (!block) return;

    const deleteFlag = block.querySelector('.delete-flag');
    if (deleteFlag) {
        deleteFlag.value = 1;
    } else {
        const input = block.querySelector('input[type=file]');
        if (input) input.value = '';
    }

    block.remove();
});
</script>
