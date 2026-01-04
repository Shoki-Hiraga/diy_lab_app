<script>
document.addEventListener('DOMContentLoaded', () => {
    const MAX_TAGS = 5;

    // サジェスト用（仮：JSだけで完結）
    const SUGGEST_TAGS = [
        'DIY','初心者','ハンドメイド','修理'
    ];

    const input = document.getElementById('tag-input');
    const tagList = document.getElementById('tag-list');
    const hiddenInput = document.getElementById('tags');

    let tags = hiddenInput.value
        ? hiddenInput.value.split(',').map(t => t.trim()).filter(Boolean)
        : [];

    let isComposing = false;
    let dragTarget = null;

    // =========================
    // 初期描画
    // =========================
    tags.forEach(tag => addTag(tag));
    updateHidden();
    updateInputState(); 

    // =========================
    // IME
    // =========================
    input.addEventListener('compositionstart', () => isComposing = true);
    input.addEventListener('compositionend', () => isComposing = false);

    // =========================
    // キー操作
    // =========================
    input.addEventListener('keydown', (e) => {
        if (isComposing) return;

        if (['Enter', ',', ' '].includes(e.key)) {
            e.preventDefault();
            commitTag();
            return;
        }

        if (e.key === 'Backspace' && input.value === '') {
            removeLastTag();
        }
    });

    input.addEventListener('blur', () => {
        commitTag();
        hideSuggest();
    });

    // =========================
    // 入力中サジェスト
    // =========================
    input.addEventListener('input', () => {
        showSuggest(input.value.trim());
    });

    // =========================
    // タグ確定
    // =========================
    function commitTag(value = input.value) {
        const tag = value.trim().replace(/^#/, '');
        if (!tag) {
            input.value = '';
            return;
        }

        if (tags.includes(tag)) {
            input.value = '';
            return;
        }

        if (tags.length >= MAX_TAGS) {
            input.value = '';
            return;
        }

        tags.push(tag);
        addTag(tag);
        updateHidden();
        updateInputState();
        input.value = '';
        hideSuggest();
    }

    // =========================
    // タグ追加
    // =========================
    function addTag(text) {
        const tag = document.createElement('span');
        tag.className = 'tag-item';
        tag.draggable = true;
        tag.dataset.tag = text;

        tag.innerHTML = `
            #${text}
            <span class="tag-remove">&times;</span>
        `;

        // 削除
        tag.querySelector('.tag-remove').addEventListener('click', () => {
            tags = tags.filter(t => t !== text);
            tag.remove();
            updateHidden();
            updateInputState();
        });

        // D&D
        tag.addEventListener('dragstart', () => {
            dragTarget = tag;
            tag.classList.add('dragging');
        });

        tag.addEventListener('dragend', () => {
            dragTarget = null;
            tag.classList.remove('dragging');
            syncOrderFromDOM();
        });

        tag.addEventListener('dragover', (e) => {
            e.preventDefault();
            if (!dragTarget || dragTarget === tag) return;
            tagList.insertBefore(dragTarget, tag);
        });

        tagList.appendChild(tag);
    }

    // =========================
    // 並び順を配列に反映
    // =========================
    function syncOrderFromDOM() {
        tags = [...tagList.children].map(el => el.dataset.tag);
        updateHidden();
    }

    // =========================
    // 最後のタグ削除
    // =========================
    function removeLastTag() {
        if (tags.length === 0) return;
        tags.pop();
        tagList.lastElementChild.remove();
        updateHidden();
        updateInputState();
    }

    // =========================
    // hidden 更新
    // =========================
    function updateHidden() {
        hiddenInput.value = tags.join(',');
    }

    // =========================
    // サジェスト
    // =========================
    let suggestBox = null;

    function showSuggest(keyword) {
        hideSuggest();
        if (!keyword) return;

        const candidates = SUGGEST_TAGS.filter(t =>
            t.toLowerCase().includes(keyword.toLowerCase()) &&
            !tags.includes(t)
        );

        if (candidates.length === 0) return;

        suggestBox = document.createElement('div');
        suggestBox.className = 'tag-suggest';

        candidates.forEach(t => {
            const item = document.createElement('div');
            item.textContent = `#${t}`;
            item.className = 'tag-suggest-item';
            item.addEventListener('mousedown', (e) => {
                e.preventDefault();
                commitTag(t);
            });
            suggestBox.appendChild(item);
        });

        input.parentNode.appendChild(suggestBox);
    }

    function hideSuggest() {
        if (suggestBox) {
            suggestBox.remove();
            suggestBox = null;
        }
    }

    function updateInputState() {
        if (tags.length >= MAX_TAGS) {
            input.disabled = true;
            input.placeholder = `タグは最大 ${MAX_TAGS} 個までです`;
            hideSuggest();
        } else {
            input.disabled = false;
            input.placeholder = 'タグを入力してEnter';
        }
    }

});


</script>
