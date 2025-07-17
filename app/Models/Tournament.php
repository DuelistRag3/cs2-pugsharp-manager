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
        'description',
        'type',
        'registration_deadline',
        'start_date',
        'end_date',
        'team_size',
        'max_teams',
        'maps_each_game',
        'maps_final_game',
        'map_rounds',
        'map_overtime_rounds',
        'status',
    ];

    protected $casts = [
        'registration_deadline' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'maps' => 'array', // Assuming maps is an array of map codes
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

            // Create matches with correct round numbering for any number of teams
            $totalRounds = (int) ceil(log($numTeams, 2));
            $matchIndex = 0;
            
            // Calculate how many teams get byes in the first round
            $nextPowerOfTwo = (int) pow(2, $totalRounds);
            $byes = $nextPowerOfTwo - $numTeams;
            $firstRoundMatches = (int) (($numTeams - $byes) / 2);
            
            // Create first round matches
            for ($matchInRound = 0; $matchInRound < $firstRoundMatches; $matchInRound++) {
                $match = new Game();
                $match->tournament_id = $this->id;
                $match->match_number = $matchIndex + 1;
                $match->round = 1;
                $match->status = 'scheduled';
                $match->save();
                $matchIndex++;
            }
            
            // Calculate teams advancing to round 2
            $teamsInNextRound = $firstRoundMatches + $byes;
            
            // Create subsequent rounds
            for ($round = 2; $round <= $totalRounds; $round++) {
                $matchesInRound = (int) ($teamsInNextRound / 2);
                
                for ($matchInRound = 0; $matchInRound < $matchesInRound; $matchInRound++) {
                    $match = new Game();
                    $match->tournament_id = $this->id;
                    $match->match_number = $matchIndex + 1;
                    $match->round = $round;
                    $match->status = 'scheduled';
                    $match->save();
                    $matchIndex++;
                }
                
                $teamsInNextRound = $matchesInRound;
            }

            // After all matches are created, assign next_game_id for bracket progression
            $this->assignNextGameIds();
        }

        if ($type === 1) { // Round Robin style
            $this->type = 1; // Set the tournament type to Round Robin
            $this->save();
            if ($numTeams < 2) {
                Debugbar::warning('Not enough teams to generate match plan.');
                return;
            }

            $numMatches = $numTeams * ($numTeams - 1) / 2; // Total matches in a round-robin tournament

            $matchNumber = 1; // Initialize match number
            for ($i = 0; $i < $numTeams; $i++) {
                for ($j = $i + 1; $j < $numTeams; $j++) {
                    $match = new Game();
                    $match->tournament_id = $this->id;
                    $match->match_number = $matchNumber++;
                    $match->team1_id = null; // Initially set team1_id to null
                    $match->team2_id = null; // Initially set team2_id to null
                    $match->round = 1; // In round-robin, all matches are in the first round
                    $match->status = 'scheduled'; // Set the status to scheduled
                    $match->save();
                }
            }
        }

        return;
    }

    public function addTeamsToMatchPlan()
    {
        // Empty teams from previous match plan
        $this->removeAllTeamsFromMatchPlan();
        $teams = $this->teams()->get();
        $teams = $teams->shuffle(); // Shuffle teams to randomize matchups

        if ($this->type === 0) { // Bracket style
            $this->addTeamsToBracket($teams);
        } else { // Round Robin or other styles
            $this->addTeamsToRoundRobin($teams);
        }
    }

    private function addTeamsToBracket($teams)
    {
        $numTeams = $teams->count();
        $totalRounds = (int) ceil(log($numTeams, 2));
        $nextPowerOfTwo = (int) pow(2, $totalRounds);
        $byes = $nextPowerOfTwo - $numTeams;

        // Convert collection to array for easier indexing
        $teamsArray = $teams->values()->all();

        // First, assign teams to first round matches
        $firstRoundGames = $this->games()->where('round', 1)->orderBy('match_number')->get();
        $teamIndex = 0;

        foreach ($firstRoundGames as $match) {
            // Assign team1
            if ($teamIndex < count($teamsArray) - $byes) {
                $match->team1_id = $teamsArray[$teamIndex]->id;
                $teamIndex++;
            }
            
            // Assign team2
            if ($teamIndex < count($teamsArray) - $byes) {
                $match->team2_id = $teamsArray[$teamIndex]->id;
                $teamIndex++;
            }
            
            $match->save();
        }

        // Now assign bye teams to round 2 matches
        if ($byes > 0) {
            $byeTeams = array_slice($teamsArray, -$byes); // Get the last 'byes' number of teams
            $round2Games = $this->games()->where('round', 2)->orderBy('match_number')->get();
            
            $byeIndex = 0;
            foreach ($round2Games as $match) {
                // Check if this match already has teams from round 1 winners
                $round1MatchesFeeding = $this->games()
                    ->where('round', 1)
                    ->where('next_game_id', $match->id)
                    ->count();
                
                // If less than 2 round 1 matches feed into this round 2 match, it needs bye teams
                $neededByes = 2 - $round1MatchesFeeding;
                
                if ($neededByes > 0 && $byeIndex < count($byeTeams)) {
                    if ($neededByes >= 2) {
                        // Both positions need bye teams
                        if (isset($byeTeams[$byeIndex])) {
                            $match->team1_id = $byeTeams[$byeIndex]->id;
                            $byeIndex++;
                        }
                        if ($byeIndex < count($byeTeams) && isset($byeTeams[$byeIndex])) {
                            $match->team2_id = $byeTeams[$byeIndex]->id;
                            $byeIndex++;
                        }
                    } else {
                        // One position needs a bye team (the other will come from round 1 winner)
                        if (isset($byeTeams[$byeIndex])) {
                            if (!$match->team1_id) {
                                $match->team1_id = $byeTeams[$byeIndex]->id;
                            } else {
                                $match->team2_id = $byeTeams[$byeIndex]->id;
                            }
                            $byeIndex++;
                        }
                    }
                    $match->save();
                }
            }
        }
    }

    private function addTeamsToRoundRobin($teams)
    {
        $numRounds = $this->games()->max('round') ?? 1;

        // For round robin, assign teams to all rounds
        for ($round = 1; $round <= $numRounds; $round++) {
            $matchesInRound = $this->games()->where('round', $round)->get();
            foreach ($matchesInRound as $match) {
                if ($teams->count() == 0) {
                    break;
                }
                if ($teams->count() >= 2) {
                    $team1 = $teams->shift();
                    $team2 = $teams->shift();
                    $match->team1_id = $team1->id;
                    $match->team2_id = $team2->id;
                    $match->save();
                } else if ($teams->count() == 1) {
                    $team = $teams->shift();
                    $match->team1_id = $team->id;
                    $match->team2_id = null;
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
    }

    private function assignNextGameIds()
    {
        $maxRound = $this->games()->max('round');
        
        // Process each round (except the final round)
        for ($round = 1; $round < $maxRound; $round++) {
            $currentRoundMatches = $this->games()
                ->where('round', $round)
                ->orderBy('match_number')
                ->get();
            
            $nextRoundMatches = $this->games()
                ->where('round', $round + 1)
                ->orderBy('match_number')
                ->get();
            
            // For bracket tournaments, we need to handle byes properly
            if ($this->type === 0) { // Bracket style
                $this->assignBracketNextGameIds($currentRoundMatches, $nextRoundMatches, $round);
            } else {
                // For other tournament types, simple assignment
                foreach ($currentRoundMatches as $index => $match) {
                    $nextMatchIndex = (int) floor($index / 2);
                    
                    if (isset($nextRoundMatches[$nextMatchIndex])) {
                        $match->next_game_id = $nextRoundMatches[$nextMatchIndex]->id;
                        $match->save();
                    }
                }
            }
        }
    }

    private function assignBracketNextGameIds($currentRoundMatches, $nextRoundMatches, $round)
    {
        $numTeams = $this->teams()->count();
        $totalRounds = (int) ceil(log($numTeams, 2));
        $nextPowerOfTwo = (int) pow(2, $totalRounds);
        $byes = $nextPowerOfTwo - $numTeams;
        
        if ($round === 1 && $byes > 0) {
            // First round with byes - special handling
            $firstRoundMatches = (int) (($numTeams - $byes) / 2);
            $teamsAdvancingFromRound1 = $firstRoundMatches; // Winners from round 1
            $totalTeamsInRound2 = $teamsAdvancingFromRound1 + $byes;
            
            // Assign first round matches to round 2 matches
            foreach ($currentRoundMatches as $index => $match) {
                // Calculate which round 2 match this round 1 match feeds into
                $nextMatchIndex = (int) floor($index / 2);
                
                if (isset($nextRoundMatches[$nextMatchIndex])) {
                    $match->next_game_id = $nextRoundMatches[$nextMatchIndex]->id;
                    $match->save();
                }
            }
        } else {
            // Standard bracket progression for rounds without byes
            foreach ($currentRoundMatches as $index => $match) {
                $nextMatchIndex = (int) floor($index / 2);
                
                if (isset($nextRoundMatches[$nextMatchIndex])) {
                    $match->next_game_id = $nextRoundMatches[$nextMatchIndex]->id;
                    $match->save();
                }
            }
        }
    }
}
