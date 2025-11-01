<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'title', 'difficulty_id', 'status', 'view_count'
    ];

    // 投稿者
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 写真＋コメントセット
    public function contents()
    {
        return $this->hasMany(PostContent::class)->orderBy('order');
    }

    // カテゴリ（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_categories', 'post_id', 'category_id');
    }

    // 使用ツール（多対多）
    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'post_tools');
    }

    // タグ（多対多）
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    // 難易度（単一）
    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class, 'difficulty_id', 'level');
    }

    // コメント（ユーザーからのコメント）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // リアクション（いいねなど）
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
}
