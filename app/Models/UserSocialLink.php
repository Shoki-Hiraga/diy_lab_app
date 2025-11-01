<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'social_platforms_id',
        'url',
    ];

    public function platform()
    {
        return $this->belongsTo(SocialPlatform::class, 'social_platforms_id');
    }
}
