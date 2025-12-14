<script>
document.addEventListener('DOMContentLoaded', () => {

    /* =========================
     * 表示件数（デバイス別）
     * ========================= */
    function getInitialVisibleCount() {
        const width = window.innerWidth;
        if (width >= 900) return 25;   // PC
        if (width >= 600) return 15;   // Tablet
        return 10;                     // SP
    }

    /* =========================
    * トグルボタン 初期表示制御（★重要）
    * ========================= */

    function updateToggleButtonVisibility(button) {
    const targetId = button.dataset.target;
    const group = document.getElementById(targetId);
    if (!group) return;

    const visibleCount = getInitialVisibleCount();
    const total = group.querySelectorAll('label').length;

    // 「全部表示できる件数」ならボタン不要
    if (total <= visibleCount) {
        button.classList.add('hidden');
    } else {
        button.classList.remove('hidden');
        // 初期表示時の文言も統一しておくと安心
        button.textContent = 'もっと見る ▼';
    }
    }

    document.querySelectorAll('.toggle-btn').forEach(button => {
        updateToggleButtonVisibility(button);
    });

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
     * カテゴリ / ツール 初期表示制御
     * ========================= */
    document.querySelectorAll('.checkbox-group').forEach(group => {
        const visibleCount = getInitialVisibleCount();

        group.querySelectorAll('label').forEach((el, i) => {
            if (i >= visibleCount) {
                el.classList.add('hidden');
            } else {
                el.classList.remove('hidden');
            }
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

            const visibleCount = getInitialVisibleCount();
            const labels = group.querySelectorAll('label');
            const hiddenItems = group.querySelectorAll('.hidden');

            if (hiddenItems.length > 0) {
                // ▼ 展開（全部表示）
                hiddenItems.forEach(el => el.classList.remove('hidden'));
                button.textContent = '閉じる';
                button.classList.remove('hidden');

            } else {
                // ▲ 折りたたみ（デバイス別表示数）
                labels.forEach((el, i) => {
                    if (i >= visibleCount) el.classList.add('hidden');
                    else el.classList.remove('hidden');
                });

                // 折りたたみ後、隠れ要素が無ければボタン非表示
                const remainHidden = group.querySelectorAll('.hidden').length;
                if (remainHidden === 0) {
                    button.classList.add('hidden');
                } else {
                    button.textContent = 'もっと見る ▼';
                    button.classList.remove('hidden');
                }
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
