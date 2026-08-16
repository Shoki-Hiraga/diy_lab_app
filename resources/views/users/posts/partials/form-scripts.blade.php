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
     * 送信前チェック（難易度・カテゴリ未選択の防止）
     * - サーバー側バリデーションで弾かれると画面が再読み込みされ、
     *   アップロード済みの写真やコメントが消えてしまうため、
     *   送信前にブラウザ側でチェックしてページ遷移自体を止める
    ===================================================== */
    const postForm = document.getElementById('post-form') || document.getElementById('post-edit-form');

    if (postForm) {
        postForm.addEventListener('submit', (e) => {

            const missing = [];

            const difficultyInput = document.getElementById('difficulty');
            if (!difficultyInput || !parseInt(difficultyInput.value, 10)) {
                missing.push('難易度');
            }

            const categoryGroup = document.getElementById('category-group');
            const hasCategory = categoryGroup
                && categoryGroup.querySelectorAll('input[type=checkbox]:checked').length > 0;
            if (!hasCategory) {
                missing.push('カテゴリ');
            }

            if (missing.length === 0) return;

            e.preventDefault();

            let box = document.getElementById('client-validation-error');
            if (!box) {
                box = document.createElement('div');
                box.id = 'client-validation-error';
                box.className = 'alert-error';
                postForm.parentElement.insertBefore(box, postForm);
            }

            box.innerHTML = '<ul>' +
                missing.map(name => `<li>・${name}を選択してください</li>`).join('') +
                '</ul>';

            box.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    /* =====================================================
     * Enter送信防止
     * - textarea は改行OK
     * - tag-input は tags-js 側に任せる
    ===================================================== */
    const form = document.getElementById('post-edit-form');
    if (!form) return;

    form.addEventListener('keydown', function(e) {

        if (e.key !== 'Enter') return;

        // textareaはOK
        if (e.target.tagName === 'TEXTAREA') return;

        // 🔥これを強化（重要）
        if (e.target.id === 'tag-input') return;

        e.preventDefault();
    });

});

</script>