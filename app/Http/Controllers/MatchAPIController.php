<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameMap;
use Illuminate\Http\Request;
use App\Models\TeamTournament;
use App\Models\GameMapPlayerScore;
use Illuminate\Support\Facades\Storage;

class MatchAPIController extends Controller
{
    protected $stats = [
                        'kills',
                        'deaths',
                        'assists',
                        'flashbang_assists',
                        'teamkills',
                        'suicides',
                        'damage',
                        'util_damage',
                        'enemies_flashed',
                        'friendlies_flashed',
                        'knife_kills',
                        'headshot_kills',
                        'roundsplayed',
                        'bomb_plants',
                        'bomb_defuses',
                        '1kill_rounds',
                        '2kill_rounds',
                        '3kill_rounds',
                        '4kill_rounds',
                        '5kill_rounds',
                        'v1',
                        'v2',
                        'v3',
                        'v4',
                        'v5',
                        'firstkill_t',
                        'firstkill_ct',
                        'firstdeath_t',
                        'firstdeath_ct',
                        'tradekill',
                        'kast',
                        'contribution_score',
                        'mvp'
                    ];

    /**
     * Generate the match configuration for the given match ID.
     *
     * @param  int  $matchid
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateMatchConfig($gameid)
    {
        $game = Game::find($gameid);
        if (!$game) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        $team1 = $game->team1;
        $team2 = $game->team2;

        if (!$team1 || !$team2) {
            return response()->json(['error' => 'Teams not assigned'], 400);
        }

        $players_team1 = [];
        $players_team2 = [];

        if($game->tournament->guest_mode) {
            $team1Players = $game->team1->players;
            foreach ($team1Players as $player) {
                $players_team1[$player->steam_id] = $player->steam_name;
            }
            $team2Players = $game->team2->players;
            foreach ($team2Players as $player) {
                $players_team2[$player->steam_id] = $player->steam_name;
            }
        } else {
            $team1Players = TeamTournament::where('tournament_id', $game->tournament->id)
                                            ->where('team_id', $game->team1->id)
                                            ->first()->players()->get();

            foreach ($team1Players as $player) {
                $players_team1[$player->user->steam_id] = $player->user->steam_name;
            }

            $team2Players = TeamTournament::where('tournament_id', $game->tournament->id)
                                            ->where('team_id', $game->team2->id)
                                            ->first()->players()->get();

            foreach ($team2Players as $player) {
                $players_team2[$player->user->steam_id] = $player->user->steam_name;
            }
        }

        if(count($players_team1) < $game->tournament->team_size || count($players_team2) < $game->tournament->team_size) {
            return response()->json(['error' => 'Not enough players in one of the teams'], 400);
        }

        $apiUri = route('api.matches.stats', ['id' => $game->id]);
        $demoUri = route('api.matches.demo', ['id' => $game->id]);
        $api_token = config('manager.api_bearer_token');

        $maplist = [];

        foreach($game->tournament->availableMaps as $map) {
            $maplist[] = $map->map_code;
        }
        
        $json = [
            'maplist' => $maplist,
            'team1' => [
                'id' => "$team1->id",
                'name' => $game->team1->name,
                'tag' => $game->team1->tag,
                'flag' => 'DE',
                'players' => $players_team1
            ],
            'team2' => [
                'id' => "$team2->id",
                'name' => $game->team2->name,
                'tag' => $game->team2->tag,
                'flag' => 'DE',
                'players' => $players_team2
            ],
            'matchid' => $game->id,
            'num_maps' => $game->maps_override ? $game->maps_override : ($game->next_game_id ? $game->tournament->maps_each_game : $game->tournament->maps_final_game),
            'players_per_team' => $game->tournament->team_size,
            'min_players_to_ready' => $game->tournament->team_size,
            'max_rounds' => $game->tournament->map_rounds,
            'max_overtime_rounds' => $game->tournament->map_overtime_rounds,
            'vote_timeout' => 60000,
            'eventula_apistats_url' => $apiUri,
            'eventula_apistats_token' => "Bearer $api_token",
            'eventula_demo_upload_url' => $demoUri,
            'vote_map' => 'de_inferno',
            'server_locale' => 'de'
        ];

        return response()->json($json);
    }

    public function goLive($gameid, $mapcount, Request $request)
    {
        $mapcount++;
        $game = Game::find($gameid);
        if (!$game) {
            return response()->json("Match not found", 404);
        }

        $map = new GameMap(
            [
                'map_number' => $mapcount,
                'map_name' => $request->mapname,
                'team1_score' => 0,
                'team2_score' => 0,
                'status' => 'ongoing',
            ]
        );

        $game->maps()->save($map);


        if(!$game->tournament->guest_mode){
            $team1Players = TeamTournament::where('tournament_id', $game->tournament->id)
                                        ->where('team_id', $game->team1->id)
                                        ->first()->players()->get();
                                    
            $team2Players = TeamTournament::where('tournament_id', $game->tournament->id)
                                        ->where('team_id', $game->team2->id)
                                        ->first()->players()->get();

            foreach ($team1Players as $player) {
                $score = new GameMapPlayerScore();
                $score->steam_id = $player->user->steam_id;
                $map->playerScores()->save($score);
            }

            foreach ($team2Players as $player) {
                $score = new GameMapPlayerScore();
                $score->steam_id = $player->user->steam_id;
                $map->playerScores()->save($score);
            }
        }

        $game->status = 'ongoing';
        $game->save();

        return response()->json("Map is now live", 200);
    }

    /**
     * Update the round information.
     *
     * @param  int  $id
     * @param  int  $round
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRound($id, $mapcount, Request $request)
    {
        $mapcount++;
        $map = GameMap::where('game_id', $id)->where('map_number', $mapcount)->first();

        if (!$map) {
            return response()->json("Match - Map combination not found", 404);
        }

        $map->team1_score = $request->team1score;
        $map->team2_score = $request->team2score;

        $map->save();

        return response()->json("Score Updated", 200);
    }

    /**
     * Update player statistics for a specific round.
     *
     * @param  int  $id
     * @param  int  $round
     * @param  string  $steamId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePlayer($gameid, $mapcount, $steamId, Request $request)
    {
        $mapcount++;
        $game = Game::find($gameid);
        if (!$game) {
            return response()->json("Match not found", 404);
        }
        $map = $game->maps()->where('map_number', $mapcount)->first();
        if (!$map) {
            return response()->json("Match - Map combination not found", 404);
        }
        if($game->tournament->guest_mode){
            $player = $game->team1->players()->where('steam_id', $steamId)->first();
            if(!$player){
                $player = $game->team2->players()->where('steam_id', $steamId)->first();
            }

            if(!$player){
                return response()->json("Player not found in the teams", 404);
            }

            $player->update($request->only($this->stats));
                

            return response()->json("Guest player statistics updated", 200);
        } else {
            foreach ($map->playerScores()->get() as $score) {
                if ($score->player->steam_id == $steamId) {
                    $score->update($request->only($this->stats));

                    return response()->json("Player statistics updated", 200);
                }
            }
        }

        return response()->json("Player not found in the map", 404);
    }

    /**
     * Finalize the map for the given match ID.
     *
     * @param  int  $map
     * @return \Illuminate\Http\JsonResponse
     */
    public function finalizeMap($gameid, $mapcount, Request $request)
    {
        $mapcount++;
        $map = GameMap::where('game_id', $gameid)->where('map_number', $mapcount)->first();
        if (!$map) {
            return response()->json("Match - Map combination not found", 404);
        }

        $game = $map->game;

        $winner = null;

        if($request->winner == $game->team1->name)
        {
            $winner = $game->team1;
        } elseif($request->winner == $game->team2->name)
        {
            $winner = $game->team2;
        }

        $map->team1_score = $request->team1score;
        $map->team2_score = $request->team2score;

        $map->winner_team_id = $winner ? $winner->id : null;

        if($map->team1_score > $map->team2_score)
        {
            $winner = $game->team1;
            $game->team1_maps_won++;
        } elseif($map->team2_score > $map->team1_score)
        {
            $winner = $game->team2;
            $game->team2_maps_won++;
        }

        $map->game->save();

        $map->status = 'completed';
        $map->save();

        return response()->json("Map finalized", 200);
    }

    /**
     * Finalize the matchup for the given match ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function finalizeMatchup($id, Request $request)
    {
        $game = Game::find($id);
        if (!$game) {
            return response()->json("Match not found", 404);
        }

        $tournament = $game->tournament;

        $teams = $tournament->teams;

        $winner = null;

        if($game->team1_maps_won > $game->team2_maps_won)
        {
            $winner = $game->team1;
        } elseif($game->team2_maps_won > $game->team1_maps_won)
        {
            $winner = $game->team2;
        }

        if (!$winner) {
            return response()->json("No winner determined", 400);
        }

        if($request->forfeit == 1)
        {
            $game->forfeit = false;
        }

        $game->winner_team_id = $winner->id;
        $game->status = 'completed';
        $game->played_at = now();
        $game->save();
        if($game->server)
        {
            $game->server->free();
        }

        // Handle bracket (type 0) vs Swiss (type 1) differently
        if ($tournament->type === 0) {
            // Bracket tournament - use next_game_id
            $next = $game->nextGame;

            if($next)
            {
                if(!$next->team1_id)
                {
                    $next->team1_id = $winner->id;
                    $next->save();
                } else {
                    $next->team2_id = $winner->id;
                    $next->save();
                }
            } else {
                // Tournament Winner
                $tournament->end_date = now();
                $tournament->status = 'completed';
                $tournament->save();
            }
        } else if ($tournament->type === 1) {
            // Swiss tournament - check if winner/loser should advance/eliminate
            // Hardcoded for 16 teams: 3 wins to advance, 3 losses to eliminate
            $totalTeams = $tournament->teams()->count();
            $winsNeeded = 3;
            
            // Calculate current record for winner and loser
            $winnerWins = 1; // This game
            $winnerLosses = 0;
            $loser = $winner->id === $game->team1_id ? $game->team2 : $game->team1;
            $loserWins = 0;
            $loserLosses = 1; // This game
            
            // Count previous games
            $completedGames = $tournament->games()->where('status', 'completed')->where('id', '!=', $game->id)->get();
            foreach ($completedGames as $completedGame) {
                // Winner's record
                if ($completedGame->team1_id === $winner->id) {
                    $completedGame->winner_team_id === $winner->id ? $winnerWins++ : $winnerLosses++;
                } elseif ($completedGame->team2_id === $winner->id) {
                    $completedGame->winner_team_id === $winner->id ? $winnerWins++ : $winnerLosses++;
                }
                
                // Loser's record
                if ($completedGame->team1_id === $loser->id) {
                    $completedGame->winner_team_id === $loser->id ? $loserWins++ : $loserLosses++;
                } elseif ($completedGame->team2_id === $loser->id) {
                    $completedGame->winner_team_id === $loser->id ? $loserWins++ : $loserLosses++;
                }
            }
            
            // Assign teams to next round games if they haven't finished
            $nextRound = $game->round + 1;
            
            // Assign winner to next game if not finished
            if ($winnerWins < $winsNeeded && $winnerLosses < $winsNeeded) {
                $this->assignTeamToNextGame($tournament, $nextRound, $winner->id, $winnerWins, $winnerLosses);
            }
            
            // Assign loser to next game if not finished
            if ($loserWins < $winsNeeded && $loserLosses < $winsNeeded) {
                $this->assignTeamToNextGame($tournament, $nextRound, $loser->id, $loserWins, $loserLosses);
            }
            
            // Check if tournament is complete (all teams finished)
            $allTeams = $tournament->teams;
            $allFinished = true;
            
            foreach ($allTeams as $team) {
                $teamWins = 0;
                $teamLosses = 0;
                
                $teamGames = $tournament->games()->where('status', 'completed')->get();
                foreach ($teamGames as $teamGame) {
                    if ($teamGame->team1_id === $team->id) {
                        $teamGame->winner_team_id === $team->id ? $teamWins++ : $teamLosses++;
                    } elseif ($teamGame->team2_id === $team->id) {
                        $teamGame->winner_team_id === $team->id ? $teamWins++ : $teamLosses++;
                    }
                }
                
                // If any team hasn't reached threshold wins or losses, tournament is not finished
                if ($teamWins < $winsNeeded && $teamLosses < $winsNeeded) {
                    $allFinished = false;
                    break;
                }
            }
            
            // Mark tournament as complete if all teams are finished
            if ($allFinished) {
                $tournament->end_date = now();
                $tournament->status = 'completed';
                $tournament->save();
            }
        }

        return response()->json("Matchup finalized", 200);
    }

    /**
     * Assign a team to the next available game in the specified round.
     *
     * @param  \App\Models\Tournament  $tournament
     * @param  int  $nextRound
     * @param  int  $teamId
     * @param  int  $wins
     * @param  int  $losses
     * @return void
     */
    private function assignTeamToNextGame($tournament, $nextRound, $teamId, $wins, $losses)
    {
        // Get games in the next round
        $nextRoundGames = $tournament->games()
            ->where('round', $nextRound)
            ->orderBy('match_number')
            ->get();

        if ($nextRoundGames->isEmpty()) {
            // No more rounds
            return;
        }

        // Calculate which score bracket this team belongs to
        $targetScore = "$wins-$losses";
        $gamesPlayed = $wins + $losses;
        
        // Get total number of teams in tournament to calculate bracket sizes
        $totalTeams = $tournament->teams()->count();
        
        if ($totalTeams == 0) {
            return;
        }
        
        // Hardcoded threshold for 16 teams
        $winsNeeded = 3;
        
        // Generate all possible scores for this round in order (highest wins first)
        // Only include brackets where teams are still playing (< threshold wins AND < threshold losses)
        $scoreGroups = [];
        for ($w = $gamesPlayed; $w >= 0; $w--) {
            $l = $gamesPlayed - $w;
            
            // Skip brackets where teams are finished (threshold+ wins or threshold+ losses)
            if ($w >= $winsNeeded || $l >= $winsNeeded) {
                continue;
            }
            
            $score = "$w-$l";
            
            // Calculate binomial coefficient C(gamesPlayed, w) - number of teams with this score
            $binomialCoeff = 1;
            for ($i = 0; $i < $w; $i++) {
                $binomialCoeff *= ($gamesPlayed - $i);
                $binomialCoeff /= ($i + 1);
            }
            
            // Number of teams with this score in the tournament
            $teamsInBracket = $binomialCoeff * ($totalTeams / pow(2, $gamesPlayed));
            // Number of games needed for these teams
            $gamesInBracket = ceil($teamsInBracket / 2);
            
            $scoreGroups[$score] = [
                'games_count' => (int)$gamesInBracket,
            ];
        }
        
        // Calculate start index for each bracket
        $currentIndex = 0;
        foreach ($scoreGroups as $score => &$group) {
            $group['start_index'] = $currentIndex;
            $currentIndex += $group['games_count'];
        }
        
        // Find the bracket for our target score
        if (!isset($scoreGroups[$targetScore])) {
            // Score bracket doesn't exist, skip
            return;
        }
        
        $bracket = $scoreGroups[$targetScore];
        $startIndex = (int)$bracket['start_index'];
        $gamesInBracket = (int)$bracket['games_count'];
        
        // Find first available slot in the target bracket
        for ($i = 0; $i < $gamesInBracket; $i++) {
            $gameIndex = $startIndex + $i;
            
            if ($gameIndex >= $nextRoundGames->count()) {
                break;
            }
            
            $nextGame = $nextRoundGames[$gameIndex];
            
            // Skip if team is already assigned to this game
            if ($nextGame->team1_id === $teamId || $nextGame->team2_id === $teamId) {
                continue;
            }
            
            if (!$nextGame->team1_id) {
                $nextGame->team1_id = $teamId;
                $nextGame->save();
                return;
            } elseif (!$nextGame->team2_id) {
                $nextGame->team2_id = $teamId;
                $nextGame->save();
                return;
            }
        }
    }

    /**
     * Pair teams for the next round in a Swiss tournament based on current records.
     *
     * @param  \App\Models\Tournament  $tournament
     * @param  int  $currentRound
     * @return void
     */
    private function pairSwissRound($tournament, $currentRound)
    {
        // Check if all games in current round are completed
        $currentRoundGames = $tournament->games()->where('round', $currentRound)->get();
        $allCompleted = $currentRoundGames->every(function($game) {
            return $game->status === 'completed';
        });

        if (!$allCompleted) {
            // Not all games in this round are finished yet
            return;
        }

        // Get all teams
        $allTeams = $tournament->guest_mode 
            ? $tournament->guestTeams 
            : $tournament->teams;

        // Calculate dynamic threshold
        $winsNeeded = (int)ceil(log($allTeams->count(), 2));

        // Calculate team records
        $teamRecords = [];
        foreach ($allTeams as $team) {
            $wins = 0;
            $losses = 0;

            $completedGames = $tournament->games()->where('status', 'completed')->get();
            foreach ($completedGames as $completedGame) {
                if ($completedGame->team1_id === $team->id) {
                    $completedGame->winner_team_id === $team->id ? $wins++ : $losses++;
                } elseif ($completedGame->team2_id === $team->id) {
                    $completedGame->winner_team_id === $team->id ? $wins++ : $losses++;
                }
            }

            // Only include teams that haven't finished
            if ($wins < $winsNeeded && $losses < $winsNeeded) {
                $teamRecords[] = [
                    'team_id' => $team->id,
                    'wins' => $wins,
                    'losses' => $losses,
                    'score' => "$wins-$losses",
                ];
            }
        }

        // Group teams by score
        $scoreGroups = [];
        foreach ($teamRecords as $record) {
            $score = $record['score'];
            if (!isset($scoreGroups[$score])) {
                $scoreGroups[$score] = [];
            }
            $scoreGroups[$score][] = $record['team_id'];
        }

        // Get next round games
        $nextRound = $currentRound + 1;
        $nextRoundGames = $tournament->games()
            ->where('round', $nextRound)
            ->orderBy('match_number')
            ->get();

        if ($nextRoundGames->isEmpty()) {
            // No more rounds - tournament is complete
            $tournament->end_date = now();
            $tournament->status = 'completed';
            $tournament->save();
            return;
        }

        // Assign teams to next round games based on score brackets
        $gameIndex = 0;
        foreach ($scoreGroups as $score => $teamIds) {
            shuffle($teamIds); // Randomize pairings within same score group
            
            for ($i = 0; $i < count($teamIds); $i += 2) {
                if (isset($teamIds[$i + 1]) && $gameIndex < $nextRoundGames->count()) {
                    $nextRoundGames[$gameIndex]->team1_id = $teamIds[$i];
                    $nextRoundGames[$gameIndex]->team2_id = $teamIds[$i + 1];
                    $nextRoundGames[$gameIndex]->save();
                    $gameIndex++;
                }
            }
        }
    }
}
