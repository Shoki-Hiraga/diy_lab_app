<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Post;
use App\Models\Reaction;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'introduction',
        'x_id',
        'youtube_url',
        'icon',
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
        ];
    }

    /* =============================
       リレーション
    ============================== */

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function socialLinks()
    {
        return $this->hasMany(UserSocialLink::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function publishedPosts()
    {
        return $this->hasMany(Post::class)
            ->where('status', Post::STATUS_PUBLISHED);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    /* =============================
       リアクション系（一覧取得用）
    ============================== */

    /**
     * いいねした投稿
     */
    public function likedPosts()
    {
        return Post::whereHas('reactions', function ($q) {
            $q->where('user_id', $this->id)
              ->whereHas('type', fn ($q) => $q->where('name', 'like'));
        });
    }

    /**
     * ブックマークした投稿
     */
    public function bookmarkedPosts()
    {
        return Post::whereHas('reactions', function ($q) {
            $q->where('user_id', $this->id)
              ->whereHas('type', fn ($q) => $q->where('name', 'bookmark'));
        });
    }

    /* =============================
       ルートキー
    ============================== */

    public function getRouteKeyName()
    {
        return 'username';
    }
}
