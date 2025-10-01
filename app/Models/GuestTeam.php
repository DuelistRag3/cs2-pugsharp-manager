<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuestTeam extends Model
{
    public $table = 'guest_teams';

    protected $fillable = [
        'tournament_id',
        'name',
        'tag',
        'flag'
    ];

    public function tournament() : BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function players() : HasMany
    {
        return $this->hasMany(GuestTeamPlayer::class);
    }
}
