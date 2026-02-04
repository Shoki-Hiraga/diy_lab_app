<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\UserProfile;
use App\Models\UserSocialLink;
use App\Models\Notification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /* =============================
       基本設定
    ============================== */

    protected $fillable = [
        'username',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 属性のキャスト定義
     * - email_verified_at: 日時として扱う
     * - password: Laravelのhashed castで保存時に自動ハッシュ
     * - is_active: true/false として扱う（DBの 0/1 を boolean に変換）
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * 利用可能ユーザーか？
     * - is_active が false の場合は停止ユーザー（BAN/凍結など）
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /* =============================
       リレーション
    ============================== */

    /**
     * プロフィール（1対1）
     * profile が存在しなくても null にならない
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class)
            ->withDefault([
                'profile' => '',
                'profile_image_url' => null,
            ]);
    }

    /**
     * SNSリンク（1対多）
     * 常に Collection が返る
     */
    public function socialLinks()
    {
        return $this->hasMany(UserSocialLink::class);
    }

    /**
     * 投稿（全件）
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 公開済み投稿
     */
    public function publishedPosts()
    {
        return $this->hasMany(Post::class)
            ->where('status', Post::STATUS_PUBLISHED);
    }

    /**
     * リアクション
     */
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    /* =============================
       リアクション系（一覧取得用）
    ============================== */

    /**
     * いいねした投稿（現在有効のみ）
     */
    public function likedPosts()
    {
        return Post::whereHas('reactions', function ($q) {
            $q->where('user_id', $this->id)
                ->where('is_active', true)
                ->whereHas('type', fn ($q) => $q->where('name', 'like'));
        });
    }

    /**
     * ブックマークした投稿（現在有効のみ）
     */
    public function bookmarkedPosts()
    {
        return Post::whereHas('reactions', function ($q) {
            $q->where('user_id', $this->id)
                ->where('is_active', true)
                ->whereHas('type', fn ($q) => $q->where('name', 'bookmark'));
        });
    }

    /**
     * 通知
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * 未読通知
     */
    public function unreadNotificationCount(): int
    {
        return $this->notifications()
            ->whereNull('read_at')
            ->whereIn('type', ['like', 'comment'])
            ->count();
    }

    /* =============================
       ルートモデルバインディング
    ============================== */

    /**
     * /creators/{username}
     */
    public function getRouteKeyName()
    {
        return 'username';
    }
}
