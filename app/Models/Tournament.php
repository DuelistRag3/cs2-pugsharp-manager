<?php

namespace App\Models;

use Barryvdh\Debugbar\Facades\Debugbar;
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

    public function start()
    {
        $this->status = 'ongoing';
        $this->start_date = now(); // Set the start date to now
        $this->save();
    }

    public function cancel()
    {

        $this->games()->each(function ($game) {

            if($game->status === 'ongoing') {
                // If the game is ongoing, we need to send the stop command to the server
                $server = $game->server;
                if ($server) {
                    try {
                        $query = new \xPaw\SourceQuery\SourceQuery();
                        $query->Connect($server->ip_address, $server->port, 1, \xPaw\SourceQuery\SourceQuery::SOURCE);
                        $query->SetRconPassword($server->rcon_password);
                        $query->Rcon('ps_stopmatch');
                        $query->Disconnect();
                    } catch (\Exception $e) {
                        Debugbar::error('Error stopping game: ' . $e->getMessage());
                    }
                }
            }

            $game->status = 'cancelled';
            $game->save();
        });
        $this->status = 'cancelled';
        $this->save();
    }

    public function generateMatchPlan()
    {
        // Logic to create the first round of the tournament
        // This could involve creating matches, assigning teams, etc.
        // For example:
        $teams = $this->teams()->get();
        $matches = [];
        if (count($teams) < 2) {
            Debugbar::error('Not enough teams to create matches');
            return;
        }
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
