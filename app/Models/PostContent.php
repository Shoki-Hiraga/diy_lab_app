<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'image_path',
        'comment',
        'order',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
