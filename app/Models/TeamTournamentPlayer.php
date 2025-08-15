<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamTournamentPlayer extends Model
{
    protected $table = 'team_tournament_player';

    protected $fillable = [
        'team_tournament_id',
        'user_id',
    ];

    public function teamInTournament(): BelongsTo
    {
        return $this->belongsTo(TeamTournament::class, 'team_tournament_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}