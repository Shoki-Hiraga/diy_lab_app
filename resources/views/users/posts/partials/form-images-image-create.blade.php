<script>
document.addEventListener('DOMContentLoaded', () => {

    function isPC() {
        return window.innerWidth >= 900;
    }

    /* =============================
       画像プレビュー
    ============================= */
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

    /* =============================
       新規ブロック追加
    ============================= */
    function addNewBlock(file = null) {

        const area = document.getElementById('photo-comment-area');
        const index = area.children.length;

        const block = document.createElement('div');
        block.className = 'photo-comment-block';

        block.innerHTML = `
            <button type="button" class="btn-remove-block">×</button>

            <div class="image-upload">
                <div class="drop-area">
                    <p class="drop-text">ドラッグ＆ドロップ</p>
                    <p class="drop-sub">またはファイルを選択</p>
                    <input type="file"
                        name="images[]"
                        id="image_${index}"
                        accept="image/*"
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

    /* =============================
       空ブロック制御
    ============================= */
    function ensureEmptyBlock() {
        const area = document.getElementById('photo-comment-area');
        const blocks = area.querySelectorAll('.photo-comment-block');

        if (blocks.length === 0) {
            addNewBlock();
            return;
        }

        const lastBlock = blocks[blocks.length - 1];
        const input = lastBlock.querySelector('input[type=file]');
        const textarea = lastBlock.querySelector('textarea');

        if (input.files.length > 0 || textarea.value.trim() !== '') {
            addNewBlock();
        }
    }

    /* =============================
       input / drag 共通処理
    ============================= */
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

            ensureEmptyBlock();
        });

        if (!isPC()) return;

        dropArea.addEventListener('dragover', () => {
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

            previewImage(firstFile, preview);

            files.forEach(file => addNewBlock(file));

            ensureEmptyBlock();
        });
    }

    /* =============================
       初期ブロック設定
    ============================= */
    const firstBlock = document.querySelector('.photo-comment-block');
    if (firstBlock) {
        const input = firstBlock.querySelector('input[type=file]');
        const preview = firstBlock.querySelector('.preview');
        const dropArea = firstBlock.querySelector('.drop-area');
        bindInput(input, preview, dropArea);
    }

    ensureEmptyBlock();

    /* =============================
       画像削除（条件付き）
    ============================= */
    document.addEventListener('click', e => {
        if (!e.target.classList.contains('btn-remove')) return;

        const block = e.target.closest('.photo-comment-block');
        const textarea = block.querySelector('textarea');
        const preview = block.querySelector('.preview');
        const input = block.querySelector('input[type=file]');

        preview.innerHTML = '';
        input.value = '';

        if (!textarea.value.trim()) {
            block.remove();
        }

        ensureEmptyBlock();
    });

    /* =============================
       ブロック削除ボタン
    ============================= */
    document.addEventListener('click', e => {
        if (!e.target.classList.contains('btn-remove-block')) return;

        const block = e.target.closest('.photo-comment-block');
        const area = document.getElementById('photo-comment-area');

        if (area.children.length > 1) {
            block.remove();
        }

        ensureEmptyBlock();
    });

});
</script>