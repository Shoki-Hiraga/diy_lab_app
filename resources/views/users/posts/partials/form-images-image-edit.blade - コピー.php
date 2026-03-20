<script>
document.addEventListener('DOMContentLoaded', () => {

    function isPC() {
        return window.innerWidth >= 900;
    }

    /* ===============================
     * プレビュー更新（既存・新規共通）
    =============================== */
    function updatePreview(file, preview) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `
                <div class="preview-wrapper">
                    <img src="${e.target.result}" class="preview-image">
                    <button type="button" class="btn-remove">×</button>
                    <div class="drop-overlay">ここにドロップで変更</div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }

    /* ===============================
     * 新規ブロック追加
    =============================== */
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
                        id="edit_image_${index}"
                        accept="image/*"
                        multiple
                        hidden>
                    <label for="edit_image_${index}" class="btn-upload">
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

        bindNewInput(input, preview, dropArea);

        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            updatePreview(file, preview);
        }
    }

    /* ===============================
     * 新規画像用イベント
    =============================== */
    function bindNewInput(input, preview, dropArea) {

        input.addEventListener('change', () => {

            const files = [...input.files];
            if (files.length === 0) return;

            files.forEach((file, index) => {
                if (index === 0) {
                    updatePreview(file, preview);
                } else {
                    addNewBlock(file);
                }
            });

            addNewBlock();
        });

        if (!isPC()) return;

        dropArea.addEventListener('dragover', e => {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('dragover');
        });

        dropArea.addEventListener('drop', e => {

            e.preventDefault();
            dropArea.classList.remove('dragover');

            const files = [...e.dataTransfer.files]
                .filter(file => file.type.startsWith('image/'));

            if (files.length === 0) return;

            const firstFile = files.shift();

            const dt = new DataTransfer();
            dt.items.add(firstFile);
            input.files = dt.files;

            updatePreview(firstFile, preview);

            files.forEach(file => addNewBlock(file));

            addNewBlock();
        });
    }

    /* ===============================
     * 既存画像：クリック & D&D差し替え
    =============================== */
    document.querySelectorAll('.photo-comment-block[data-existing]').forEach(block => {

        const preview = block.querySelector('.preview');
        const input = block.querySelector('input[type=file]');

        if (!preview || !input) return;

        // オーバーレイ追加
        const overlay = document.createElement('div');
        overlay.className = 'drop-overlay';
        overlay.innerText = 'ここにドロップで変更';
        preview.appendChild(overlay);

        // クリックで選択
        preview.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-remove')) return;
            input.click();
        });

        // 通常選択
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;
            updatePreview(file, preview);
        });

        // ドラッグ
        preview.addEventListener('dragover', e => {
            e.preventDefault();
            preview.classList.add('dragover');
        });

        preview.addEventListener('dragleave', () => {
            preview.classList.remove('dragover');
        });

        preview.addEventListener('drop', e => {
            e.preventDefault();
            preview.classList.remove('dragover');

            const file = e.dataTransfer.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;

            updatePreview(file, preview);
        });

    });

    /* ===============================
     * 初期：新規ブロック
    =============================== */
    document.querySelectorAll(
        '#photo-comment-area .photo-comment-block:not([data-existing])'
    ).forEach(block => {

        const input = block.querySelector('input[type=file]');
        const preview = block.querySelector('.preview');
        const dropArea = block.querySelector('.drop-area');

        if (!input || !dropArea) return;

        bindNewInput(input, preview, dropArea);
    });

});


/* ===============================
 * 削除ボタン
=============================== */
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('btn-remove')) {

        const block = e.target.closest('.photo-comment-block');

        const deleteFlag = block.querySelector('.delete-flag');

        if (deleteFlag) {
            deleteFlag.value = 1;
            block.style.display = 'none';
        } else {
            block.remove();
        }
    }
});
</script>