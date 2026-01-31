<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\ReactionType;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    /**
     * リアクションのトグル（like / bookmark など）
     */
    public function toggle(Post $post, string $type): RedirectResponse
    {
        $userId = Auth::id();

        // リアクション種別取得（存在しない type は 404）
        $reactionType = ReactionType::where('name', $type)->firstOrFail();

        // 既存リアクション取得（unique 制約前提）
        $reaction = Reaction::where([
            'user_id' => $userId,
            'post_id' => $post->id,
            'reaction_type_id' => $reactionType->id,
        ])->first();

        /*
         |--------------------------------------------------
         | リアクションの状態切り替え
         |--------------------------------------------------
         */
        if ($reaction) {
            // ON <-> OFF 切り替え
            $reaction->is_active = ! $reaction->is_active;
            $reaction->save();

            $activated = $reaction->is_active;
        } else {
            // 初回リアクション
            $reaction = Reaction::create([
                'user_id'           => $userId,
                'post_id'           => $post->id,
                'reaction_type_id'  => $reactionType->id,
                'is_active'         => true,
            ]);

            $activated = true;
        }

        /*
         |--------------------------------------------------
         | 通知作成（いいね ON 時のみ）
         |--------------------------------------------------
         */
        if (
            $type === 'like' &&
            $activated &&
            $post->user_id !== $userId
        ) {
            Notification::create([
                'user_id'  => $post->user_id, // 通知を受け取る人（投稿主）
                'actor_id' => $userId,         // アクションした人
                'post_id'  => $post->id,
                'type'     => 'like',
            ]);
        }

        return back();
    }
}
