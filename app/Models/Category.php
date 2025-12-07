<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Category extends Model
{
    protected $fillable = ['name'];

    /**
     * すべての投稿（下書き・非公開含む）
     */
    public function posts()
    {
        return $this->belongsToMany(
            Post::class,
            'post_categories',
            'category_id',
            'post_id'
        );
    }

    /**
     * 公開中の投稿のみ
     */
    public function publishedPosts()
    {
        return $this->belongsToMany(
                Post::class,
                'post_categories',
                'category_id',
                'post_id'
            )
            ->where('status', Post::STATUS_PUBLISHED);
    }
}
