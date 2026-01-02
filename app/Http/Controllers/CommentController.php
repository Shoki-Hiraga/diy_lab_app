<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * コメント投稿（AJAX）
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ]);

        // AJAX用：コメント1件のHTMLを返す
        return view('components.comments.item', [
            'comment' => $comment->load('user'),
        ]);
    }

    /**
     * コメント更新（AJAX・インライン編集）
     */
    public function update(Request $request, Comment $comment)
    {
        abort_unless($comment->user_id === auth()->id(), 403);

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return response()->json([
            'body' => $comment->body,
        ]);
    }

    /**
     * コメント削除（AJAX）
     */
    public function destroy(Comment $comment)
    {
        abort_unless($comment->user_id === auth()->id(), 403);

        $comment->delete();

        return response()->noContent();
    }
}
