@auth
<form id="comment-form">
    @csrf
    <textarea name="body" required placeholder="コメントを入力してください"></textarea>
    <button type="submit">投稿する</button>
</form>
@else
<div class="comment-login-guide">
    ログインするとコメントできます
</div>
@endauth
