document.addEventListener('DOMContentLoaded', () => {

    const commentsWrapper = document.querySelector('.comments');
    if (!commentsWrapper) return;

    const postId = commentsWrapper.dataset.postId;
    const commentList = document.getElementById('comment-list');
    const commentCount = document.getElementById('comment-count');
    const commentForm = document.getElementById('comment-form');

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    /**
     * ============================
     * コメント投稿
     * ============================
     */
    if (commentForm) {
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const textarea = commentForm.querySelector('textarea[name="body"]');
            const body = textarea.value.trim();
            if (!body) return;

            try {
                const response = await fetch(`/posts/${postId}/comments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'text/html',
                    },
                    body: JSON.stringify({ body }),
                });

                if (!response.ok) throw new Error('投稿失敗');

                const html = await response.text();

                // 先頭に追加
                commentList.insertAdjacentHTML('afterbegin', html);

                // 件数更新
                commentCount.textContent =
                    Number(commentCount.textContent) + 1;

                textarea.value = '';

            } catch (error) {
                alert('コメントの投稿に失敗しました');
                console.error(error);
            }
        });
    }

    /**
     * ============================
     * 削除・編集（イベント委譲）
     * ============================
     */
    commentList.addEventListener('click', async (e) => {

        const commentItem = e.target.closest('.comment-item');
        if (!commentItem) return;

        const commentId = commentItem.dataset.id;

        /**
         * 削除
         */
        if (e.target.classList.contains('comment-delete-btn')) {
            if (!confirm('このコメントを削除しますか？')) return;

            try {
                const response = await fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });

                if (!response.ok) throw new Error('削除失敗');

                commentItem.remove();

                commentCount.textContent =
                    Number(commentCount.textContent) - 1;

            } catch (error) {
                alert('削除に失敗しました');
                console.error(error);
            }
        }

        /**
         * 編集（インライン）
         */
        if (e.target.classList.contains('comment-edit-btn')) {

            const bodyEl = commentItem.querySelector('.comment-body');
            const originalText = bodyEl.textContent.trim();

            // 二重編集防止
            if (bodyEl.querySelector('textarea')) return;

            bodyEl.innerHTML = `
                <textarea class="comment-edit-textarea">${originalText}</textarea>
                <div class="comment-edit-actions">
                    <button class="comment-save-btn">保存</button>
                    <button class="comment-cancel-btn">キャンセル</button>
                </div>
            `;
        }

        /**
         * 編集キャンセル
         */
        if (e.target.classList.contains('comment-cancel-btn')) {
            const bodyEl = commentItem.querySelector('.comment-body');
            const textarea = bodyEl.querySelector('textarea');

            bodyEl.textContent = textarea.value;
        }

        /**
         * 編集保存
         */
        if (e.target.classList.contains('comment-save-btn')) {
            const bodyEl = commentItem.querySelector('.comment-body');
            const textarea = bodyEl.querySelector('textarea');
            const newBody = textarea.value.trim();

            if (!newBody) {
                alert('コメントを入力してください');
                return;
            }

            try {
                const response = await fetch(`/comments/${commentId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ body: newBody }),
                });

                if (!response.ok) throw new Error('更新失敗');

                const data = await response.json();
                bodyEl.textContent = data.body;

            } catch (error) {
                alert('更新に失敗しました');
                console.error(error);
            }
        }

    });

});

/**
 * ============================
 * 返信コメント（AJAX）
 * ============================
 */
commentList.addEventListener('click', async (e) => {

    if (!e.target.classList.contains('comment-reply-btn')) return;

    const commentItem = e.target.closest('.comment-item');
    const parentId = commentItem.dataset.id;

    // すでにフォームがあれば出さない
    if (commentItem.querySelector('.reply-form')) return;

    const formHtml = `
        <form class="reply-form">
            <textarea required
                placeholder="返信を入力してください"></textarea>
            <div class="reply-actions">
                <button type="submit">返信する</button>
                <button type="button" class="reply-cancel-btn">キャンセル</button>
            </div>
        </form>
    `;

    commentItem.insertAdjacentHTML('beforeend', formHtml);
});

/**
 * 返信送信・キャンセル
 */
commentList.addEventListener('click', async (e) => {

    const replyForm = e.target.closest('.reply-form');
    if (!replyForm) return;

    const commentItem = e.target.closest('.comment-item');
    const parentId = commentItem.dataset.id;

    // キャンセル
    if (e.target.classList.contains('reply-cancel-btn')) {
        replyForm.remove();
        return;
    }

    // 送信
    if (e.target.type === 'submit') {
        e.preventDefault();

        const textarea = replyForm.querySelector('textarea');
        const body = textarea.value.trim();
        if (!body) return;

        try {
            const response = await fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'text/html',
                },
                body: JSON.stringify({
                    body,
                    parent_comment_id: parentId,
                }),
            });

            if (!response.ok) throw new Error();

            const html = await response.text();

            const replies = commentItem.querySelector('.comment-replies');
            replies.insertAdjacentHTML('beforeend', html);

            replyForm.remove();

        } catch {
            alert('返信に失敗しました');
        }
    }
});

/**
 * ============================
 * 相対時間表示（共通・最優先）
 * ============================
 */

function formatRelativeTime(isoString) {
    const now = new Date();
    const time = new Date(isoString);
    const diffSeconds = Math.floor((now - time) / 1000);

    if (isNaN(diffSeconds)) return '';

    if (diffSeconds < 60) return 'たった今';
    if (diffSeconds < 3600) return `${Math.floor(diffSeconds / 60)}分前`;
    if (diffSeconds < 86400) return `${Math.floor(diffSeconds / 3600)}時間前`;
    if (diffSeconds < 172800) return '昨日';

    return `${Math.floor(diffSeconds / 86400)}日前`;
}

function updateRelativeTimes() {
    document.querySelectorAll('.comment-date[data-time]').forEach(el => {
        const iso = el.dataset.time;
        if (!iso) return;

        let rel = el.querySelector('.comment-relative-time');
        if (!rel) {
            rel = document.createElement('span');
            rel.className = 'comment-relative-time';
            el.appendChild(rel);
        }

        rel.textContent = ` ・ ${formatRelativeTime(iso)}`;
    });
}

// DOMに依存しないので即実行OK
document.addEventListener('DOMContentLoaded', () => {
    updateRelativeTimes();
    setInterval(updateRelativeTimes, 60 * 1000);
});


/**
 * ============================
 * コメントAJAX（DOM依存）
 * ============================
 */
document.addEventListener('DOMContentLoaded', () => {

    const commentsWrapper = document.querySelector('.comments');
    if (!commentsWrapper) return;

    // ← ここに投稿・削除・編集・返信のJS
});
