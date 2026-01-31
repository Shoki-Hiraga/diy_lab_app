<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\UserProfile;
use App\Models\UserSocialLink;
use App\Models\Notification;

class User extends Authenticatable
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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
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
            ->where('is_active', true) //アクティブ非アクティブに変更
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
            ->where('is_active', true) //アクティブ非アクティブに変更
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
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
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
