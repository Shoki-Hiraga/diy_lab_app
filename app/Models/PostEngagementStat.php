<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostEngagementStat extends Model
{
    protected $fillable = [
        'post_id',
        'date',
        'view_count',
        'like_count',
        'reaction_count',
        'score',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
