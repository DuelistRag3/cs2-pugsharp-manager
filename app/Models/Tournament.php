<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    //

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'location',
        'description',
    ];

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
