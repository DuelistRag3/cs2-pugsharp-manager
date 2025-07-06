<?php

namespace App\Models;

use App\Models\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMap extends Model
{
    protected $fillable = [
        'map_number',
        'map_name',
        'team1_score',
        'team2_score',
        'status',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function playerScores() : HasMany
    {
        return $this->hasMany(GameMapPlayerScore::class, 'game_map_id');
    }
}
