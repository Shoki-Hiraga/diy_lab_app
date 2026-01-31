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
   * 共通：AJAXヘルパー
   * ============================
   */
  async function requestJson(url, { method = 'GET', body = null } = {}) {
    const headers = {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json',
    };

    if (body !== null) {
      headers['Content-Type'] = 'application/json';
    }

    const response = await fetch(url, {
      method,
      headers,
      body: body !== null ? JSON.stringify(body) : null,
    });

    // 204 の場合は本文がない
    if (response.status === 204) {
      return { response, data: null };
    }

    // JSON じゃないレスポンスが返ってきた時でも落ちないようにする
    let data = null;
    const contentType = response.headers.get('content-type') || '';
    if (contentType.includes('application/json')) {
      data = await response.json();
    } else {
      const text = await response.text();
      data = { success: false, message: text };
    }

    return { response, data };
  }

  function get422Message(data) {
    // Laravelのvalidate()は errors: { field: [msg...] } を返すことが多い
    if (data?.errors) {
      const firstKey = Object.keys(data.errors)[0];
      if (firstKey && Array.isArray(data.errors[firstKey]) && data.errors[firstKey][0]) {
        return data.errors[firstKey][0];
      }
    }
    return data?.message || '入力内容を確認してください';
  }

  function handleHttpError(response, data) {
    // 状態に応じてメッセージ分岐（最低限）
    if (response.status === 401) return 'ログインしてください';
    if (response.status === 403) return '権限がありません';
    if (response.status === 419) return 'セッションが切れました。再読み込みしてください';
    if (response.status === 422) return get422Message(data);
    return data?.message || '通信に失敗しました';
  }

  /**
   * ============================
   * 相対時間表示
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

  function updateRelativeTimes(scope = document) {
    scope.querySelectorAll('.comment-date[data-time]').forEach((el) => {
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

  updateRelativeTimes();
  setInterval(() => updateRelativeTimes(), 60 * 1000);

  /**
   * ============================
   * コメント投稿（親）
   * ============================
   */
  if (commentForm) {
    commentForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const textarea = commentForm.querySelector('textarea[name="body"]');
      const submitBtn = commentForm.querySelector('button[type="submit"]');

      const body = textarea?.value?.trim() || '';
      if (!body) return;

      // 二重送信防止
      if (submitBtn) submitBtn.disabled = true;

      try {
        const { response, data } = await requestJson(`/posts/${postId}/comments`, {
          method: 'POST',
          body: { body },
        });

        if (!response.ok || !data?.success) {
          const msg = handleHttpError(response, data);
          throw new Error(msg);
        }

        // 先頭に追加
        commentList.insertAdjacentHTML('afterbegin', data.html);

        // 追加した要素だけ相対時間更新
        const firstItem = commentList.firstElementChild;
        if (firstItem) updateRelativeTimes(firstItem);

        // 件数更新
        if (commentCount) {
          commentCount.textContent = String(Number(commentCount.textContent) + 1);
        }

        textarea.value = '';

      } catch (error) {
        alert(error.message || 'コメントの投稿に失敗しました');
        console.error(error);
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  }

  /**
   * ============================
   * イベント委譲：削除・編集・返信
   * ============================
   */
  commentsWrapper.addEventListener('click', async (e) => {
    const commentItem = e.target.closest('.comment-item');
    if (!commentItem) return;

    const commentId = commentItem.dataset.id;

    /**
     * 削除
     */
    if (e.target.classList.contains('comment-delete-btn')) {
      if (!confirm('このコメントを削除しますか？')) return;

      try {
        const { response, data } = await requestJson(`/comments/${commentId}`, {
          method: 'DELETE',
        });

        if (!response.ok) {
          const msg = handleHttpError(response, data);
          throw new Error(msg);
        }

        commentItem.remove();

        if (commentCount) {
          commentCount.textContent = String(Number(commentCount.textContent) - 1);
        }

      } catch (error) {
        alert(error.message || '削除に失敗しました');
        console.error(error);
      }
      return;
    }

    /**
     * 編集（インライン表示）
     */
    if (e.target.classList.contains('comment-edit-btn')) {
      const bodyEl = commentItem.querySelector('.comment-body');
      if (!bodyEl) return;

      // 二重編集防止
      if (bodyEl.querySelector('textarea')) return;

      const originalText = bodyEl.textContent.trim();

      bodyEl.innerHTML = `
        <textarea class="comment-edit-textarea">${escapeHtml(originalText)}</textarea>
        <div class="comment-edit-actions">
          <button type="button" class="comment-save-btn">保存</button>
          <button type="button" class="comment-cancel-btn">キャンセル</button>
        </div>
      `;
      return;
    }

    /**
     * 編集キャンセル
     */
    if (e.target.classList.contains('comment-cancel-btn')) {
      const bodyEl = commentItem.querySelector('.comment-body');
      const textarea = bodyEl?.querySelector('textarea');
      if (!bodyEl || !textarea) return;

      // 元の表示に戻す（textareaの内容を表示）
      bodyEl.textContent = textarea.value;
      return;
    }

    /**
     * 編集保存
     */
    if (e.target.classList.contains('comment-save-btn')) {
      const bodyEl = commentItem.querySelector('.comment-body');
      const textarea = bodyEl?.querySelector('textarea');
      if (!bodyEl || !textarea) return;

      const newBody = textarea.value.trim();
      if (!newBody) {
        alert('コメントを入力してください');
        return;
      }

      try {
        const { response, data } = await requestJson(`/comments/${commentId}`, {
          method: 'PUT',
          body: { body: newBody },
        });

        if (!response.ok || !data?.success) {
          const msg = handleHttpError(response, data);
          throw new Error(msg);
        }

        bodyEl.textContent = data.body;

      } catch (error) {
        alert(error.message || '更新に失敗しました');
        console.error(error);
      }
      return;
    }

    /**
     * 返信ボタン押下 → フォーム表示
     */
    if (e.target.classList.contains('comment-reply-btn')) {
      // すでに表示されていたら何もしない
      if (commentItem.querySelector('.reply-form')) return;

      const formHtml = `
        <form class="reply-form">
          <textarea required placeholder="返信を入力してください"></textarea>
          <div class="reply-actions">
            <button type="submit" class="reply-submit-btn">返信する</button>
            <button type="button" class="reply-cancel-btn">キャンセル</button>
          </div>
        </form>
      `;

      commentItem.insertAdjacentHTML('beforeend', formHtml);
      return;
    }

    /**
     * 返信キャンセル
     */
    if (e.target.classList.contains('reply-cancel-btn')) {
      const form = e.target.closest('.reply-form');
      if (form) form.remove();
      return;
    }
  });

  /**
   * ============================
   * 返信送信（submitイベントで拾うのが安定）
   * ============================
   */
  commentsWrapper.addEventListener('submit', async (e) => {
    const form = e.target.closest('.reply-form');
    if (!form) return;

    e.preventDefault();

    const commentItem = form.closest('.comment-item');
    if (!commentItem) return;

    const textarea = form.querySelector('textarea');
    const body = textarea?.value?.trim() || '';
    if (!body) return;

    const parentId = commentItem.dataset.id;
    const repliesContainer = commentItem.querySelector('.comment-replies');
    if (!repliesContainer) return;

    const submitBtn = form.querySelector('.reply-submit-btn');
    if (submitBtn) submitBtn.disabled = true;

    try {
      const { response, data } = await requestJson(`/posts/${postId}/comments`, {
        method: 'POST',
        body: {
          body,
          parent_comment_id: parentId,
        },
      });

      if (!response.ok || !data?.success) {
        const msg = handleHttpError(response, data);
        throw new Error(msg);
      }

      repliesContainer.insertAdjacentHTML('beforeend', data.html);

      // 追加した返信だけ相対時間更新
      const lastReply = repliesContainer.lastElementChild;
      if (lastReply) updateRelativeTimes(lastReply);

      form.remove();

    } catch (error) {
      alert(error.message || '返信の投稿に失敗しました');
      console.error(error);
    } finally {
      if (submitBtn) submitBtn.disabled = false;
    }
  });

  /**
   * ============================
   * 返信コメントのトグル制御
   * ============================
   */
  document.addEventListener('click', (e) => {
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

    const match = toggle.textContent.match(/\d+/);
    const count = match ? match[0] : '0';

    toggle.textContent = isOpen
      ? `${count}件の返信`
      : `${count}件の返信を非表示`;
  });

  /**
   * ============================
   * XSS対策：編集 textarea の中身用
   * ============================
   */
  function escapeHtml(str) {
    return String(str)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }
});
