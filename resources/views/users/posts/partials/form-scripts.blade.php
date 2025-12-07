<script>
document.addEventListener('DOMContentLoaded', () => {

    /* =========================
     * 星評価
     * ========================= */
    document.querySelectorAll('.stars .star').forEach(star => {
        star.addEventListener('click', () => {
            const value = star.dataset.value;
            document.getElementById('difficulty').value = value;

            document.querySelectorAll('.stars .star').forEach(s => {
                s.textContent =
                    s.dataset.value <= value ? '★' : '☆';
            });
        });
    });

    /* =========================
     * カテゴリ / ツール トグル
     * ========================= */
    document.querySelectorAll('.toggle-btn').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.dataset.target;
            const group = document.getElementById(targetId);
            if (!group) return;

            const hidden = group.querySelectorAll('.hidden');

            if (hidden.length) {
                hidden.forEach(el => el.classList.remove('hidden'));
                button.textContent = '閉じる';
            } else {
                group.querySelectorAll('label').forEach((el, i) => {
                    if (i >= 10) el.classList.add('hidden');
                });
                button.textContent = 'もっと見る';
            }
        });
    });

    /* =========================
     * 画像プレビュー
     * ========================= */
    function bindImageInput(input) {
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;

            const preview =
                input.closest('.image-upload').querySelector('.preview');

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

            const inputs = [...document.querySelectorAll('#photo-comment-area input[type=file]')];
            if (inputs.at(-1) === input) addNewBlock();
        });
    }

    document
        .querySelectorAll('#photo-comment-area input[type=file]')
        .forEach(bindImageInput);

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
        }

        block.remove();
    });

});
</script>
