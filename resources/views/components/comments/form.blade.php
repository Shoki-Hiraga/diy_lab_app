{{-- コメント投稿 --}}
<div class="comment-form">
    @auth
        <form id="comment-form">
            @csrf
            <textarea name="body"
                        rows="4"
                        required
                        class="comment-textarea"
                        placeholder="コメントを入力してください"></textarea>

            <button type="submit" class="comment-submit">
                投稿する
            </button>
        </form>
    @else
        <div class="comment-login-guide">
            <p class="comment-login-text">
                ログインするとコメントできます
            </p>
            <div class="comment-login-actions">
                <a href="{{ route('login') }}" class="btn-nav">
                    🔑 ログイン
                </a>
                <a href="{{ route('register') }}" class="btn-register">
                    ✨ 会員登録
                </a>
            </div>
        </div>
        </div>
    @endauth
</div>
