<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメント投稿（AJAX）
     */
    public function store(Request $request, Post $post)
    {
        // 🔐 未ログイン防止（AJAXでも必須）
        abort_unless(Auth::check(), 401);

        // ✅ バリデーション
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
            'parent_comment_id' => 'nullable|exists:comments,id',
        ]);

        $userId = Auth::id();

        // ✅ コメント作成
        $comment = $post->comments()->create([
            'user_id'           => $userId,
            'body'              => $validated['body'],
            'parent_comment_id' => $validated['parent_comment_id'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 🔔 通知処理
        |--------------------------------------------------------------------------
        */

        // ① 投稿主への通知（自分以外）
        if ($post->user_id !== $userId) {
            Notification::create([
                'user_id'  => $post->user_id,
                'actor_id' => $userId,
                'post_id'  => $post->id,
                'type'     => 'comment',
            ]);
        }

        // ② 親コメントへの通知（自分以外・重複防止）
        if (!empty($validated['parent_comment_id'])) {
            $parent = Comment::find($validated['parent_comment_id']);

            if (
                $parent &&
                $parent->user_id !== $userId &&
                $parent->user_id !== $post->user_id
            ) {
                Notification::create([
                    'user_id'  => $parent->user_id,
                    'actor_id' => $userId,
                    'post_id'  => $post->id,
                    'type'     => 'comment',
                ]);
            }
        }

        // ✅ JSONでHTMLを返す（AJAX用 正解）
        return response()->json([
            'success' => true,
            'html' => view('components.comments.item', [
                'comment' => $comment->fresh(['user']),
                'isReply' => !empty($validated['parent_comment_id']),
            ])->render(),
        ]);
    }

    /**
     * コメント更新（AJAX・インライン編集）
     */
    public function update(Request $request, Comment $comment)
    {
        // 🔐 権限チェック
        abort_unless($comment->user_id === Auth::id(), 403);

        // ✅ バリデーション
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // ✅ 更新
        $comment->update([
            'body' => $validated['body'],
        ]);

        // ❗ 更新では通知しない（UX的に正解）

        return response()->json([
            'success' => true,
            'body' => $comment->body,
        ]);
    }

    /**
     * コメント削除（AJAX）
     */
    public function destroy(Comment $comment)
    {
        // 🔐 権限チェック
        abort_unless($comment->user_id === Auth::id(), 403);

        // ✅ 削除
        $comment->delete();

        // ❗ 削除でも通知しない

        // ✅ fetch と相性の良い 204
        return response()->noContent();
    }
}
