<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\ReactionType;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function toggle(Post $post, string $type)
    {
        $reactionType = ReactionType::where('name', $type)->firstOrFail();

        $reaction = Reaction::where([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'reaction_type_id' => $reactionType->id,
        ])->first();

        if ($reaction) {
            // ❌ deleteしない
            $reaction->is_active = ! $reaction->is_active;
            $reaction->save();
        } else {
            Reaction::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
                'reaction_type_id' => $reactionType->id,
                'is_active' => true,
            ]);
        }


        return back();
    }
}
