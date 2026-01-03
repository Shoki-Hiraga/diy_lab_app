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
 * 返信コメント（AJAX・安定版）
 * ============================
 */
document.addEventListener('DOMContentLoaded', () => {

    const commentsWrapper = document.querySelector('.comments');
    if (!commentsWrapper) return;

    const postId = commentsWrapper.dataset.postId;
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    commentsWrapper.addEventListener('click', async (e) => {

        /**
         * 返信ボタン押下 → フォーム表示
         */
        if (e.target.classList.contains('comment-reply-btn')) {
            const commentItem = e.target.closest('.comment-item');
            if (!commentItem) return;

            // すでに表示されていたら何もしない
            if (commentItem.querySelector('.reply-form')) return;

            const formHtml = `
                <form class="reply-form">
                    <textarea required
                        placeholder="返信を入力してください"></textarea>
                    <div class="reply-actions">
                        <button type="submit" class="reply-submit-btn">
                            返信する
                        </button>
                        <button type="button" class="reply-cancel-btn">
                            キャンセル
                        </button>
                    </div>
                </form>
            `;

            commentItem.insertAdjacentHTML('beforeend', formHtml);
        }

        /**
         * 返信キャンセル
         */
        if (e.target.classList.contains('reply-cancel-btn')) {
            const form = e.target.closest('.reply-form');
            if (form) form.remove();
        }

        /**
         * 返信送信
         */
        if (e.target.classList.contains('reply-submit-btn')) {
            e.preventDefault();

            const form = e.target.closest('.reply-form');
            const commentItem = e.target.closest('.comment-item');
            if (!form || !commentItem) return;

            const textarea = form.querySelector('textarea');
            const body = textarea.value.trim();
            if (!body) return;

            const parentId = commentItem.dataset.id;
            const repliesContainer =
                commentItem.querySelector('.comment-replies');

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

                repliesContainer.insertAdjacentHTML('beforeend', html);
                form.remove();

            } catch (err) {
                alert('返信の投稿に失敗しました');
                console.error(err);
            }
        }

    });
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

/**
 * ============================
 * コメント返信のトグル制御
 * ============================
 */
document.addEventListener('click', function (e) {

  const toggle = e.target.closest('.comment-replies-toggle');
  if (!toggle) return;

  e.preventDefault();

  const targetId = toggle.dataset.target;
  const replies = document.getElementById(targetId);
  if (!replies) return;

  const isOpen = !replies.hasAttribute('hidden');

  if (isOpen) {
    replies.setAttribute('hidden', '');
    toggle.classList.remove('is-open');
  } else {
    replies.removeAttribute('hidden');
    toggle.classList.add('is-open');
  }

  const count = toggle.textContent.match(/\d+/)[0];
  toggle.textContent = isOpen
    ? `${count}件の返信`
    : `${count}件の返信を非表示`;
});
