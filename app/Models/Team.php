<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{

    protected $fillable = [
        'name',
        'tag',
        'flag',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class);
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
