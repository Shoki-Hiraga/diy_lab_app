<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReactionType extends Model
{
    protected $fillable = ['name'];

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
}
