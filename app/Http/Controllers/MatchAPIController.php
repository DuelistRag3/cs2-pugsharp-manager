<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MatchAPIController extends Controller
{

    /**
     * Generate the match configuration for the given match ID.
     *
     * @param  int  $matchid
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateMatchConfig($matchid)
    {
        $match = Game::find($matchid);
        if (!$match) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        $players_team1 = $match->team1->players->pluck('steam_name', 'steam_id')->toArray();
        $players_team2 = $match->team2->players->pluck('steam_name', 'steam_id')->toArray();

        $team1id = $match->team1->id;
        $team2id = $match->team2->id;

        $apiUri = route('api.matches.stats', ['id' => $match->id]);
        $demoUri = route('api.matches.demo', ['id' => $match->id]);
        $api_token = config('manager.api_bearer_token');

        $json = [
            'maplist' => [
                'de_vertigo',
                'de_dust2',
                'de_inferno',
                'de_mirage',
                'de_nuke',
                'de_overpass',
                'de_ancient'
            ],
            'team1' => [
                'id' => "$team1id",
                'name' => $match->team1->name,
                'tag' => $match->team1->tag,
                'flag' => 'DE',
                'players' => $players_team1
            ],
            'team2' => [
                'id' => "$team2id",
                'name' => $match->team2->name,
                'tag' => $match->team2->tag,
                'flag' => 'DE',
                'players' => $players_team2
            ],
            'matchid' => "$match->match_number",
            'num_maps' => $match->tournament->matchup_rounds,
            'players_per_team' => 5,
            'min_players_to_ready' => 5,
            'max_rounds' => 24,
            'max_overtime_rounds' => 6,
            'vote_timeout' => 60000,
            'eventula_apistats_url' => $apiUri,
            'eventula_apistats_token' => "Bearer $api_token",
            'eventula_demo_upload_url' => $demoUri,
            'vote_map' => 'de_inferno',
            'server_locale' => 'de'
        ];

        return response()->json($json);
    }

    /**
     * Handle the request when a round is finished.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function roundFinished($id, Request $request)
    {
        // Log::info('Round finished request received', ['id' => $id, 'request' => $request->all()]);
        // // Logic to handle when a round is finished
        // // This could involve updating the game status, scores, etc.
        // return response()->json(['message' => 'Round finished', 'id' => $id]);

        return response()->json([
            'message' => 'Round finished',
            'id' => $id,
            'token' => $request->bearerToken(),
        ]);
    }
}
