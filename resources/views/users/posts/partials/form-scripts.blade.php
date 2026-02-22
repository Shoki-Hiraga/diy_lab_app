<script>
document.addEventListener('DOMContentLoaded', () => {

    /* =====================================================
     * ブラウザのデフォルトD&D無効化
    ===================================================== */
    ['dragenter','dragover','dragleave','drop'].forEach(eventName => {
        window.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    /* =====================================================
     * 表示件数（デバイス別）
    ===================================================== */
    function getInitialVisibleCount() {
        const width = window.innerWidth;
        if (width >= 900) return 25;
        if (width >= 600) return 15;
        return 10;
    }

    /* =====================================================
     * トグル初期表示制御
    ===================================================== */
    function updateToggleButtonVisibility(button) {
        const targetId = button.dataset.target;
        const group = document.getElementById(targetId);
        if (!group) return;

        const visibleCount = getInitialVisibleCount();
        const total = group.querySelectorAll('label').length;

        if (total <= visibleCount) {
            button.classList.add('hidden');
        } else {
            button.classList.remove('hidden');
            button.textContent = 'もっと見る ▼';
        }
    }

    document.querySelectorAll('.toggle-btn').forEach(button => {
        updateToggleButtonVisibility(button);
    });

    /* =====================================================
     * 星評価
    ===================================================== */
    document.querySelectorAll('.stars .star').forEach(star => {
        star.addEventListener('click', () => {
            const value = star.dataset.value;
            document.getElementById('difficulty').value = value;

            document.querySelectorAll('.stars .star').forEach(s => {
                s.textContent = s.dataset.value <= value ? '★' : '☆';
            });
        });
    });

    /* =====================================================
     * カテゴリ / ツール 初期表示制御
    ===================================================== */
    document.querySelectorAll('.checkbox-group').forEach(group => {
        const visibleCount = getInitialVisibleCount();
        group.querySelectorAll('label').forEach((el, i) => {
            if (i >= visibleCount) el.classList.add('hidden');
        });
    });

    /* =====================================================
     * カテゴリ / ツール トグル
    ===================================================== */
    document.querySelectorAll('.toggle-btn').forEach(button => {
        button.addEventListener('click', () => {

            const targetId = button.dataset.target;
            const group = document.getElementById(targetId);
            if (!group) return;

            const visibleCount = getInitialVisibleCount();
            const labels = group.querySelectorAll('label');
            const hiddenItems = group.querySelectorAll('.hidden');

            if (hiddenItems.length > 0) {
                hiddenItems.forEach(el => el.classList.remove('hidden'));
                button.textContent = '閉じる';
            } else {
                labels.forEach((el, i) => {
                    if (i >= visibleCount) el.classList.add('hidden');
                });
                button.textContent = 'もっと見る ▼';
            }
        });
    });

    /* =====================================================
       画像エリア（ドラッグ対応版）
    ===================================================== */

    function isPC() {
        return window.innerWidth >= 900;
    }

    function previewImage(file, preview) {
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
    }

    function addNewBlock(file = null) {

        const area = document.getElementById('photo-comment-area');
        const index = area.children.length;

        const block = document.createElement('div');
        block.className = 'photo-comment-block';

        block.innerHTML = `
            <div class="image-upload">
                <div class="drop-area">
                    <p class="drop-text">ドラッグ＆ドロップ</p>
                    <p class="drop-sub">またはファイルを選択</p>
                    <input type="file"
                        name="images[]"
                        id="image_${index}"
                        accept="image/*"
                        multiple
                        hidden>
                    <label for="image_${index}" class="btn-upload">
                        ファイルを選択
                    </label>
                </div>
                <div class="preview post-preview"></div>
            </div>
            <textarea name="comments[]" placeholder="この写真の説明を入力..."></textarea>
        `;

        area.appendChild(block);

        const input = block.querySelector('input[type=file]');
        const preview = block.querySelector('.preview');
        const dropArea = block.querySelector('.drop-area');

        bindInput(input, preview, dropArea);

        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            previewImage(file, preview);
        }
    }

    function bindInput(input, preview, dropArea) {

        input.addEventListener('change', () => {

            const files = [...input.files];
            if (files.length === 0) return;

            files.forEach((file, index) => {

                if (index === 0) {
                    previewImage(file, preview);
                } else {
                    addNewBlock(file);
                }

            });

            // 最後に空ブロック追加
            addNewBlock();
        });

        if (!isPC()) return;

        dropArea.addEventListener('dragover', () => {
            dropArea.classList.add('dragover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('dragover');
        });

        dropArea.addEventListener('drop', e => {

            dropArea.classList.remove('dragover');

            const files = [...e.dataTransfer.files]
                .filter(file => file.type.startsWith('image/'));

            if (files.length === 0) return;

            // 1枚目は今のブロックへ
            const firstFile = files.shift();

            const dt = new DataTransfer();
            dt.items.add(firstFile);
            input.files = dt.files;

            previewImage(firstFile, preview);

            // 2枚目以降は新規
            files.forEach(file => addNewBlock(file));

            // 最後に空ブロック1つ
            addNewBlock();
        });
    }

    /* ===== 最初のブロック初期化 ===== */
    const firstBlock = document.querySelector('.photo-comment-block');
    if (firstBlock) {
        const input = firstBlock.querySelector('input[type=file]');
        const preview = firstBlock.querySelector('.preview');
        const dropArea = firstBlock.querySelector('.drop-area');
        bindInput(input, preview, dropArea);
    }

    /* =====================================================
     * × 削除
    ===================================================== */
    document.addEventListener('click', e => {
        if (!e.target.classList.contains('btn-remove')) return;
        e.target.closest('.photo-comment-block').remove();
    });

</script>