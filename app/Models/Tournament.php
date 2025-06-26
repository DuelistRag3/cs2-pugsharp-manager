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

    public function createFirstRound()
    {
        // Logic to create the first round of the tournament
        // This could involve creating matches, assigning teams, etc.
        // For example:
        $teams = $this->teams()->get();
        $matches = [];
        
        for ($i = 0; $i < count($teams); $i += 2) {
            if (isset($teams[$i + 1])) {
                $matches[] = [
                    'match_number' => ($i / 2) + 1,
                    'team1_id' => $teams[$i]->id,
                    'team2_id' => $teams[$i + 1]->id,
                    'tournament_id' => $this->id,
                    'status' => 'scheduled',
                    'team1_score' => null,
                    'team2_score' => null,
                ];
            }
        }

        // Assuming you have a Game model to handle games
        foreach ($matches as $matchData) {
            Game::create($matchData);
        }
    }
}
