<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialPlatform extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function links()
    {
        return $this->hasMany(UserSocialLink::class, 'social_platforms_id');
    }
}
