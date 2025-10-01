<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    protected $fillable = [
        'match_number',
        'team1_id',
        'team2_id',
        'tournament_id',
        'score_team1',
        'score_team2',
        'status',
    ]; 

    public function tournament() : BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team1() : BelongsTo
    {
        if ($this->tournament->guest_mode) {
            return $this->belongsTo(GuestTeam::class, 'team1_id');
        }

        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2() : BelongsTo
    {
        if ($this->tournament->guest_mode) {
            return $this->belongsTo(GuestTeam::class, 'team2_id');
        }

        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function server() : HasOne
    {
        return $this->hasOne(Server::class, 'id', 'server_id');
    }

    public function maps() : HasMany
    {
        return $this->hasMany(GameMap::class, 'game_id');
    }

    public function nextGame() : HasOne
    {
        return $this->hasOne(Game::class, 'id', 'next_game_id');
    }

    public function start()
    {
        $this->status = 'ongoing';
        $this->save();
    }

    public function finish()
    {
        $this->status = 'completed';
        $this->save();
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
    }

    public function winnerTeam() : BelongsTo
    {
        if($this->tournament->guest_mode) {
            return $this->belongsTo(GuestTeam::class, 'winner_team_id');
        }
        return $this->belongsTo(Team::class, 'winner_team_id');
    }
}
