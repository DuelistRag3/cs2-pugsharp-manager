<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function tournaments(): HasMany
    {
        return $this->hasMany(TeamInTournament::class);
    }
}
