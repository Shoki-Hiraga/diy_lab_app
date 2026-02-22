<script>
document.addEventListener('DOMContentLoaded', () => {

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
            previewImage(file, preview);
        }
    }

    function bindNewInput(input, preview, dropArea) {

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

            previewImage(firstFile, preview);

            files.forEach(file => addNewBlock(file));

            addNewBlock();
        });
    }

    /* 初期：既存ではないブロックのみ有効化 */
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
</script>