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

        if(count($players_team1) < $game->tournament->team_size || count($players_team2) < $game->tournament->team_size) {
            return response()->json(['error' => 'Not enough players in one of the teams'], 400);
        }

        $apiUri = route('api.matches.stats', ['id' => $game->id]);
        $demoUri = route('api.matches.demo', ['id' => $game->id]);
        $api_token = config('manager.api_bearer_token');

        $maplist = [];

        foreach($game->tournament->maps as $map) {
            $maplist[] = $map;
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
            'matchid' => "$game->id",
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
        $mapcount +=1;
        Storage::disk('local')->put("match_{$gameid}_map_{$mapcount}_golive.json", json_encode($request->all()));

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

        foreach ($game->team1->players as $player) {
            $score = new GameMapPlayerScore();
            $score->player_id = $player->id;
            $map->playerScores()->save($score);
        }

        foreach ($game->team2->players as $player) {
            $score = new GameMapPlayerScore();
            $score->player_id = $player->id;
            $map->playerScores()->save($score);
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

        $game = Game::find($gameid);
        if (!$game) {
            return response()->json("Match not found", 404);
        }
        $map = $game->maps()->where('map_number', $mapcount)->first();
        if (!$map) {
            return response()->json("Match - Map combination not found", 404);
        }

        foreach ($map->playerScores()->get() as $score) {
            if ($score->player->steam_id == $steamId) {
                $score->update($request->only([
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
                ]));

                return response()->json("Player statistics updated", 200);
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
        $map = GameMap::where('game_id', $gameid)->where('map_number', $mapcount)->first();
        if (!$map) {
            return response()->json("Match - Map combination not found", 404);
        }

        $game = $map->game;

        $tournament = $game->tournament;

        $teams = $tournament->teams;

        $winner = null;

        foreach($teams as $team)
        {
            if($team->name == $request->winner)
            {
                $winner = $team;
            }
        }

        $map->team1_score = $request->team1score;
        $map->team2_score = $request->team2score;

        $map->winner_team_id = $winner ? $winner->id : null;

        if ($request->team1score > $request->team2score) {
            $map->game->team1_maps_won += 1;
        } elseif ($request->team1score < $request->team2score) {
            $map->game->team2_maps_won += 1;
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

        foreach($teams as $team)
        {
            if($team->name == $request->winner)
            {
                $winner = $team;
            }
            
        }

        if (!$winner) {
            return response()->json("No winner determined", 400);
        }

        if($request->forfeit == 1)
        {
            $game->forfeit = true;
        }

        $game->winner_team_id = $winner->id;
        $game->status = 'completed';
        $game->played_at = now();
        $game->save();
        if($game->server)
        {
            $game->server->free();
        }

        $next = $game->nextGame;

        if(!$next)
        {
            if(!$next->team1_id)
        {
            $next->team1_id = $winner->id;
            $next->save();
        } else {
            $next->team2_id = $winner->id;
            $next->save();
        }
        }

        return response()->json("Matchup finalized", 200);
    }
}
