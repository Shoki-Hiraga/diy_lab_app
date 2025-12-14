<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'introduction',
        'x_id',
        'youtube_url',
        'icon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function socialLinks()
    {
        return $this->hasMany(UserSocialLink::class);
    }

    public function getRouteKeyName()
    {
        return 'username';
    }
    
    /**
     * 投稿
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 公開済み投稿のみ
     */
    public function publishedPosts()
    {
        return $this->hasMany(Post::class)
            ->where('status', Post::STATUS_PUBLISHED);
    }
    
}
