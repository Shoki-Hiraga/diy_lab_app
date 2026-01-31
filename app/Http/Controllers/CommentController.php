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
        $request->validate([
            'body' => 'required|string|max:1000',
            'parent_comment_id' => 'nullable|exists:comments,id',
        ]);

        $userId = Auth::id();

        // コメント作成
        $comment = $post->comments()->create([
            'user_id'           => $userId,
            'body'              => $request->body,
            'parent_comment_id' => $request->parent_comment_id,
        ]);

        /*
         |--------------------------------------------------
         | 🔔 コメント通知
         |--------------------------------------------------
         */

        // ① 投稿主への通知（自分以外）
        if ($post->user_id !== $userId) {
            Notification::create([
                'user_id'  => $post->user_id, // 投稿主
                'actor_id' => $userId,         // コメントした人
                'post_id'  => $post->id,
                'type'     => 'comment',
            ]);
        }

        // ② 親コメントへの返信通知（自分以外・重複防止）
        if ($request->parent_comment_id) {
            $parentComment = Comment::find($request->parent_comment_id);

            if (
                $parentComment &&
                $parentComment->user_id !== $userId &&
                $parentComment->user_id !== $post->user_id
            ) {
                Notification::create([
                    'user_id'  => $parentComment->user_id, // 親コメントの投稿者
                    'actor_id' => $userId,
                    'post_id'  => $post->id,
                    'type'     => 'comment',
                ]);
            }
        }

        return view('components.comments.item', [
            'comment' => $comment->load('user'),
            'isReply' => (bool) $request->parent_comment_id,
        ]);
    }

    /**
     * コメント更新（AJAX・インライン編集）
     */
    public function update(Request $request, Comment $comment)
    {
        abort_unless($comment->user_id === Auth::id(), 403);

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        // ❗ 更新では通知しない（UX的に正解）

        return response()->json([
            'body' => $comment->body,
        ]);
    }

    /**
     * コメント削除（AJAX）
     */
    public function destroy(Comment $comment)
    {
        abort_unless($comment->user_id === Auth::id(), 403);

        $comment->delete();

        // ❗ 削除でも通知しない

        return response()->noContent();
    }
}
