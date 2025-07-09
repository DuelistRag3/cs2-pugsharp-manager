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

            if ($game->status === 'ongoing') {
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

    public function generateMatchPlan($type)
    {
        $numTeams = $this->teams()->count();

        if ($numTeams < 2) {
            Debugbar::warning('Not enough teams to generate match plan.');
            return;
        }

        if ($type === 0) { // Bracket style
            $this->type = 0; // Set tournament type to Bracket
            $this->save(); // Save the tournament type

            $numMatches = $numTeams - 1; // In a single-elimination tournament, there are always one less match than the number of teams

            for ($i = 0; $i < $numMatches; $i++) {
                $match = new Game();
                $match->tournament_id = $this->id;
                $match->match_number = $i + 1;
                $match->round = (int) ceil(log($numTeams, 2)) - (int) floor(log($i + 1, 2)); // Calculate the round based on the match number
                $match->status = 'scheduled';
                $match->save();
            }
        }

        if ($type === 1) { // Round Robin style
            $this->type = 1; // Set the tournament type to Round Robin
            $this->save();
        }

        return;
    }

    public function addTeamsToMatchPlan()
    {
        $teams = $this->teams()->get();

        // Fill the rounds with teams, if the number of teams is not a power of 2, random teams will be given a bye
        $teams = $teams->shuffle(); // Shuffle teams to randomize matchups

        $numRounds = $this->games()->max('round') ?? 1; // Get the maximum round number or default to 1

        // dd($numRounds);

        // In the first round we will pair teams and cut them out of the remaining teams
        for ($round = 1; $round <= $numRounds; $round++) {
            $matchesInRound = $this->games()->where('round', $round)->get();
            foreach ($matchesInRound as $match) {
                if ($teams->count() == 0) {
                    break; // Not enough teams to continue
                }
                if ($teams->count() >= 2) {
                    $team1 = $teams->shift(); // Get the first team
                    $team2 = $teams->shift(); // Get the second team
                    $match->team1_id = $team1->id;
                    $match->team2_id = $team2->id;
                    $match->save();
                } else if ($teams->count() == 1) {
                    $team = $teams->shift(); // Get the next team
                    $match->team1_id = $team->id; // Assign the team to team
                    $match->team2_id = null; // Set the second team to null for now
                    $match->save();
                }
            }
        }
    }

    public function removeAllTeamsFromMatchPlan()
    {
        // Reset all games in the tournament
        $this->games()->each(function ($game) {
            $game->team1_id = null;
            $game->team2_id = null;
            $game->status = 'scheduled'; // Reset the status to scheduled
            $game->save();
        });

        // Reset the status of the tournament
        $this->status = 'scheduled';
        $this->save();
    }
}
