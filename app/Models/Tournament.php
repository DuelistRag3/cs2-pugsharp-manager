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
        if ($type === 0) { // Bracket style
            $this->type = 0; // Set tournament type to Bracket
            $this->save();
            Debugbar::info('Generating match plan for tournament: ' . $this->name);
            $teams = $this->teams()->get();
            $numTeams = $teams->count();
            Debugbar::info('Number of teams: ' . $numTeams);
            if ($numTeams < 2) {
                Debugbar::warning('Not enough teams to generate match plan.');
                return;
            }
            $rounds = ceil(log($numTeams, 2)); // Calculate the number of rounds needed
            Debugbar::info('Number of rounds: ' . $rounds);
            $matches = [];
            for ($round = 0; $round < $rounds; $round++) {
                $numMatches = ceil($numTeams / pow(2, $round + 1)); // Calculate the number of matches in this round
                Debugbar::info('Round ' . ($round + 1) . ' has ' . $numMatches . ' matches.');
                for ($match = 0; $match < $numMatches; $match++) {
                    $matchup = new Game();
                    $matchup->tournament_id = $this->id;
                    $matchup->match_number = $match + 1; // Match number starts from 1
                    $matchup->status = 'scheduled'; // Set initial status to scheduled
                    $matchup->save();
                    $matches[] = $matchup;
                    Debugbar::info('Created match: ' . $matchup->id . ' for round ' . ($round + 1) . ', match number ' . ($match + 1));
                }
                // If there are not enough teams for the next round, break the loop
                if ($numTeams < 2) {
                    Debugbar::warning('Not enough teams for the next round, breaking the loop.');
                    break;
                }
            }

            // Assign teams to the first round matches
            $firstRoundMatches = array_slice($matches, 0, $numTeams / 2);
            Debugbar::info('Assigning teams to first round matches.');
            foreach ($firstRoundMatches as $index => $match) {
                if (isset($teams[$index * 2]) && isset($teams[$index * 2 + 1])) {
                    $match->team1_id = $teams[$index * 2]->id;
                    $match->team2_id = $teams[$index * 2 + 1]->id;
                    $match->save();
                    Debugbar::info('Assigned teams: ' . $teams[$index * 2]->name . ' vs ' . $teams[$index * 2 + 1]->name . ' to match: ' . $match->id);
                } else {
                    Debugbar::warning('Not enough teams to assign to match: ' . $match->id);
                }
            }

            Debugbar::info('Match plan generated successfully for tournament: ' . $this->name);
            return $matches;
        }

        if($type === 1) { // Round Robin style
            $this->type = 1; // Set the tournament type to Round Robin
            $this->save();
            Debugbar::info('Generating round robin match plan for tournament: ' . $this->name);
            $teams = $this->teams()->get();
            $numTeams = $teams->count();
            Debugbar::info('Number of teams: ' . $numTeams);
            if ($numTeams < 2) {
                Debugbar::warning('Not enough teams to generate match plan.');
                return;
            }
            $matches = [];
            
            return $matches;
        }
    }
}
