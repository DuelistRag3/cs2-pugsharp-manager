<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameMap;
use Illuminate\Http\Request;
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

        $players_team1 = $team1->players->pluck('steam_name', 'steam_id')->toArray();
        $players_team2 = $team2->players->pluck('steam_name', 'steam_id')->toArray();

        if(count($players_team1) < $game->tournament->team_size || count($players_team2) < $game->tournament->team_size) {
            return response()->json(['error' => 'Not enough players in one of the teams'], 400);
        }

        $apiUri = route('api.matches.stats', ['id' => $game->id]);
        $demoUri = route('api.matches.demo', ['id' => $game->id]);
        $api_token = config('manager.api_bearer_token');

        switch ($game->tournament->maps_each_game) {
            case 0:
                $rounds = 1; // BO1
                break;
            case 1:
                $rounds = 3; // BO3
                break;
            case 2:
                $rounds = 5; // BO5
                break;
            default:
                $rounds = 1; // Default to BO1 if not set
        }

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
            'num_maps' => $rounds,
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

        $game->status = 'ongoing';
        $game->save();

        return response()->json($json);
    }

    public function goLive($mapcount, Request $request)
    {
        Storage::disk('local')->put("match_{$request->id}_map_{$mapcount}_golive.json", json_encode($request->all()));

        $game = Game::find($request->id);
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
        $mapcount = +1;

        // Storage::disk('local')->put("match_{$id}_map_{$mapcount}_updatetround.json", json_encode($request->all()));

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
    public function updatePlayer($id, $mapcount, $steamId, Request $request)
    {
        $mapcount = +1;
        // $player = Player::where('steam_id', $steamId)->first();

        $game = Game::find($id);
        if (!$game) {
            return response()->json("Match not found", 404);
        }

        // $map = GameMap::where('game_id', $id)->where('map_number', $mapcount)->first();
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
    public function finalizeMap($mapcount, Request $request)
    {
        // Storage::disk('local')->put("match_{$request->id}_map_{$mapcount}_finished.json", json_encode($request->all()));

        $map = GameMap::where('game_id', $request->id)->where('map_number', $mapcount)->first();
        if (!$map) {
            return response()->json("Match - Map combination not found", 404);
        }

        $map->team1_score = $request->team1score;
        $map->team2_score = $request->team2score;

        if ($request->team1score > $request->team2score) {
            $map->game->team1_maps_won += 1;
        } elseif ($request->team1score < $request->team2score) {
            $map->game->team2_maps_won += 1;
        }

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

        // Storage::disk('local')->put("match_{$id}_finalized.json", json_encode($request->all()));

        $game = Game::find($id);
        if (!$game) {
            return response()->json("Match not found", 404);
        }

        if($game->team1_maps_won > $game->team2_maps_won) {
            $winner = $game->team1;
        } elseif($game->team1_maps_won < $game->team2_maps_won) {
            $winner = $game->team2;
        }

        if (!$winner) {
            return response()->json("No winner determined", 400);
        }

        $game->status = 'completed';
        $game->played_at = now();
        $game->save();
        $game->server->free();

        $next = $game->nextGame;

        if(!$next->team1_id)
        {
            $next->team1_id = $winner->id;
            $next->save();
        } else {
            $next->team2_id = $winner->id;
            $next->save();
        }

        return response()->json("Matchup finalized", 200);
    }
}
