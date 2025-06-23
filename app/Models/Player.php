<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    protected $fillable = [
        'steam_id',
        'steam_name',
        'steam_avatar',
        'steam_url',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
