<?php

namespace App\Models;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\RconController;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Symfony\Component\ErrorHandler\Debug;

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

    public function teams()
    {
        if ($this->guest_mode) {
            return $this->hasMany(GuestTeam::class);
        } else {
            return $this->belongsToMany(Team::class);
        }
    }

    public function maps(): BelongsToMany
    {
        return $this->belongsToMany(TournamentMap::class);
    }

    public function availableMaps(): BelongsToMany
    {
        return $this->belongsToMany(AvailableMaps::class, 'map_tournament', 'tournament_id', 'map_id');
    }

    public function registrationAllowed()
    {
        if( $this->status !== 'scheduled') {
            return false;
        }

        if( $this->teams()->count() >= $this->max_teams) {
            return false;
        }

        if( now()->greaterThanOrEqualTo(new \DateTime($this->registration_deadline ?? $this->start_date))) {
            return false;
        }

        if(!$this->guest_mode && auth()->guest()) {
            return false;
        }

        return true;
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
                    $rcon = new RconController();
                    $rcon->sendCommand($server->id, 'ps_stopmatch');
                }
            }

            $game->cancel(); // Cancel the game
        });
        $this->status = 'cancelled';
        $this->end_date = now(); // Set the end date to now
        $this->save();
    }

    public function generateMatchPlan($type)
    {
        $numTeams = $this->teams()->count();

        if ($numTeams < 2) {
            LivewireAlert::title('Not enough teams to generate match plan.')
            ->warning()
            ->toast()
            ->position('top-end')
            ->show();
            return;
        }

        if ($type === 0) { // Playofs / Bracket
            $this->type = 0; // Set tournament type to Bracket

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

        if ($type === 1) { // Group stages (Swiss System with eliminations (only half the teams advance after group stage))
            if($numTeams !== 16) {
                LivewireAlert::title('Swiss System match plan generation is only supported for 16 teams.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
                return;
            }
            $this->type = 1; // Set the tournament type to Group Stage

            // Swiss System: Hardcoded for 16 teams, 3 wins to advance, 3 losses to eliminate
            // Expected structure: 8+8+8+6+3 = 33 games across 5 rounds
            
            $matchIndex = 0;
            $gamesPerRound = [8, 8, 8, 6, 3]; // Hardcoded for 16 teams
            
            // Create matches for each round
            for ($round = 1; $round <= count($gamesPerRound); $round++) {
                $matchesInRound = $gamesPerRound[$round - 1];
                
                for ($matchInRound = 0; $matchInRound < $matchesInRound; $matchInRound++) {
                    $match = new Game();
                    $match->tournament_id = $this->id;
                    $match->match_number = $matchIndex + 1;
                    $match->round = $round;
                    $match->status = 'scheduled';
                    $match->save();
                    $matchIndex++;
                }
            }
        }

        $this->save();

        LivewireAlert::title(__('manager.tournament_messages.matchplan_generated'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();

        return;
    }

    public function addTeamsToMatchPlan()
    {
        $this->removeAllTeamsFromMatchPlan();
        $teams = $this->teams()->get();
        $teams = $teams->shuffle();

        if ($this->type === 0) {
            $this->addTeamsToBracket($teams);
        } else {
            $this->addTeamsToRoundRobin($teams);
        }
    }

    private function addTeamsToBracket($teams)
    {
        $numTeams = $teams->count();
        $totalRounds = (int) ceil(log($numTeams, 2));
        $nextPowerOfTwo = (int) pow(2, $totalRounds);
        $byes = $nextPowerOfTwo - $numTeams;

        $teamsArray = $teams->values()->all();

        $firstRoundGames = $this->games()->where('round', 1)->orderBy('match_number')->get();
        $teamIndex = 0;

        foreach ($firstRoundGames as $match) {
            if ($teamIndex < count($teamsArray) - $byes) {
                $match->team1_id = $teamsArray[$teamIndex]->id;
                $teamIndex++;
            }

            if ($teamIndex < count($teamsArray) - $byes) {
                $match->team2_id = $teamsArray[$teamIndex]->id;
                $teamIndex++;
            }

            $match->save();
        }

        if ($byes > 0) {
            $byeTeams = array_slice($teamsArray, -$byes);
            $round2Games = $this->games()->where('round', 2)->orderBy('match_number')->get();

            $byeIndex = 0;
            foreach ($round2Games as $match) {
                $round1MatchesFeeding = $this->games()
                    ->where('round', 1)
                    ->where('next_game_id', $match->id)
                    ->count();

                $neededByes = 2 - $round1MatchesFeeding;

                if ($neededByes > 0 && $byeIndex < count($byeTeams)) {
                    if ($neededByes >= 2) {
                        if (isset($byeTeams[$byeIndex])) {
                            $match->team1_id = $byeTeams[$byeIndex]->id;
                            $byeIndex++;
                        }
                        if ($byeIndex < count($byeTeams) && isset($byeTeams[$byeIndex])) {
                            $match->team2_id = $byeTeams[$byeIndex]->id;
                            $byeIndex++;
                        }
                    } else {
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
        $this->games()->each(function ($game) {
            $game->team1_id = null;
            $game->team2_id = null;
            $game->status = 'scheduled';
            $game->save();
        });
    }

    private function assignNextGameIds()
    {
        $maxRound = $this->games()->max('round');

        for ($round = 1; $round < $maxRound; $round++) {
            $currentRoundMatches = $this->games()
                ->where('round', $round)
                ->orderBy('match_number')
                ->get();

            $nextRoundMatches = $this->games()
                ->where('round', $round + 1)
                ->orderBy('match_number')
                ->get();

            if ($this->type === 0) {
                $this->assignBracketNextGameIds($currentRoundMatches, $nextRoundMatches, $round);
            } else {
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
            $firstRoundMatches = (int) (($numTeams - $byes) / 2);
            $teamsAdvancingFromRound1 = $firstRoundMatches;

            foreach ($currentRoundMatches as $index => $match) {
                $nextMatchIndex = (int) floor($index / 2);

                if (isset($nextRoundMatches[$nextMatchIndex])) {
                    $match->next_game_id = $nextRoundMatches[$nextMatchIndex]->id;
                    $match->save();
                }
            }
        } else {
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
