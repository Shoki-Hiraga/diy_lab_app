<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    /**
     * 投稿（多対多）
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }

    /**
     * 公開済み投稿のみ
     */
    public function publishedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_tags')
            ->where('status', Post::STATUS_PUBLISHED);
    }
}
