<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Difficulty extends Model
{
    protected $fillable = ['name'];

    /**
     * すべての投稿（下書き・非公開含む）
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 公開中の投稿のみ
     */
    public function publishedPosts()
    {
        return $this->hasMany(Post::class)
            ->where('status', Post::STATUS_PUBLISHED);
    }
}
