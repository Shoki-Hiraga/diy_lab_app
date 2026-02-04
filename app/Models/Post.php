<?php

namespace App\Models;
use App\Models\Tag;
use App\Models\PostTag;
use App\Models\Comment;
use App\Models\ReactionType;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // ========================
    // ステータス定数
    // ========================
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

    // ========================
    // リレーション
    // ========================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contents()
    {
        return $this->hasMany(PostContent::class)->orderBy('order');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'post_categories',
            'post_id',
            'category_id'
        );
    }

    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'post_tools');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function rootComments()
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_comment_id')
            ->with('replies.user')
            ->latest();
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function likes()
    {
        return $this->reactions()
            ->where('is_active', true)
            ->whereHas('type', fn ($q) => $q->where('name', 'like'));
    }

    public function bookmarks()
    {
        return $this->reactions()
            ->where('is_active', true)
            ->whereHas('type', fn ($q) => $q->where('name', 'bookmark'));
    }

    /**
     * 日別エンゲージメント統計
     */
    public function engagementStats()
    {
        return $this->hasMany(PostEngagementStat::class);
    }

    // ========================
    // スコープ
    // ========================

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeWithCommentCount($query)
    {
        return $query->withCount('comments');
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

    // ========================
    // アクセサ
    // ========================

    public function getMainImagePathAttribute()
    {
        $firstContent = $this->contents->first();
        return $firstContent ? $firstContent->image_path : null;
    }

    // ========================
    // ユーティリティ
    // ========================

    public function isReactedBy(string $type, ?int $userId = null): bool
    {
        if (!$userId) return false;

        return $this->reactions()
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->whereHas('type', fn ($q) => $q->where('name', $type))
            ->exists();
    }

    // ========================
    // ★ エンゲージメント集計（将来拡張前提）
    // ========================

    /**
     * 今日のエンゲージメント統計を取得（なければ作成）
     */
    public function todayEngagementStat(): PostEngagementStat
    {
        return $this->engagementStats()->firstOrCreate(
            [
                'date' => Carbon::today(),
            ],
            [
                'view_count' => 0,
                'like_count' => 0,
                'reaction_count' => 0,
                'score' => 0,
            ]
        );
    }

    /**
     * PV加算（表示用＋集計用）
     */
    public function incrementView(): void
    {
        $this->increment('view_count');
        $this->todayEngagementStat()->increment('view_count');
    }

    /**
     * いいね ON
     */
    public function likeOn(): void
    {
        $this->todayEngagementStat()->increment('like_count');
    }

    /**
     * いいね OFF
     */
    public function likeOff(): void
    {
        $stat = $this->todayEngagementStat();

        if ($stat->like_count > 0) {
            $stat->decrement('like_count');
        }
    }

    /**
     * リアクション ON
     */
    public function reactionOn(): void
    {
        $this->todayEngagementStat()->increment('reaction_count');
    }

    /**
     * リアクション OFF
     */
    public function reactionOff(): void
    {
        $stat = $this->todayEngagementStat();

        if ($stat->reaction_count > 0) {
            $stat->decrement('reaction_count');
        }
    }
}
