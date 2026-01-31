<?php

namespace App\Models;
use App\Models\Tag;
use App\Models\PostTag;
use App\Models\Comment;
use App\Models\ReactionType;

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
        return $this->belongsTo(Difficulty::class);
    }

    /**
     * コメント（全件）
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 親コメント（投稿に直接ついたもの）
     */
    public function rootComments()
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_comment_id')
            ->with('replies.user') // 返信も一緒に取得
            ->latest();
    }

    /**
     * リアクション
     */
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * いいね
     */
    public function likes()
    {
        return $this->reactions()
            ->where('is_active', true) //アクティブ非アクティブに変更
            ->whereHas('type', fn ($q) => $q->where('name', 'like'));
    }

    /**
     * ブックマーク
     */
    public function bookmarks()
    {
        return $this->reactions()
            ->where('is_active', true) //アクティブ非アクティブに変更
            ->whereHas('type', fn ($q) => $q->where('name', 'bookmark'));
    }

    /**
     * 公開済みのみ取得するスコープ
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * コメント件数を必ず付与するスコープ
     */
    public function scopeWithCommentCount($query)
    {
        return $query->withCount('comments');
    }

    /**
     * 代表画像（最初の画像）
     */
    public function getMainImagePathAttribute()
    {
        $firstContent = $this->contents->first();

        return $firstContent ? $firstContent->image_path : null;
    }

    public function isReactedBy(string $type, ?int $userId = null): bool
    {
        if (!$userId) return false;

        return $this->reactions()
            ->where('user_id', $userId)
            ->where('is_active', true) //アクティブ非アクティブに変更
            ->whereHas('type', fn ($q) => $q->where('name', $type))
            ->exists();
    }

    public function scopeWithListRelations($query)
    {
        return $query->with([
            'contents',
            'categories',
            'user.profile',
            'reactions.type',
        ]);
    }

}
