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
        'status', // e.g., scheduled, completed, etc.
    ]; 

    public function tournament() : BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team1() : BelongsTo
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2() : BelongsTo
    {
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
}
