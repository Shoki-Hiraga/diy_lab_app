<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // ✅ ステータス定数
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_PRIVATE = 'private';

    protected $fillable = [
        'user_id',
        'title',
        'difficulty_id',
        'status',
        'view_count',
    ];

    /**
     * 投稿者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 写真＋コメントセット
     */
    public function contents()
    {
        return $this->hasMany(PostContent::class)->orderBy('order');
    }

    /**
     * カテゴリ（多対多）
     */
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'post_categories',
            'post_id',
            'category_id'
        );
    }

    /**
     * 使用ツール（多対多）
     */
    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'post_tools');
    }

    /**
     * タグ（多対多）
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    /**
     * 難易度
     */
    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class, 'difficulty_id', 'level');
    }

    /**
     * コメント
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * リアクション
     */
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * 公開済みのみ取得するスコープ
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * 代表画像（最初の画像）
     */
    public function getMainImagePathAttribute()
    {
        $firstContent = $this->contents->first();

        return $firstContent ? $firstContent->image_path : null;
    }
}
