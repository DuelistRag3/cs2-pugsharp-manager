<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }

    public function generateMatchConfig($matchid)
    {
        $match = Game::find($matchid);
        if (!$match) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        $players_team1 = $match->team1->players->pluck('steam_id', 'steam_name')->toArray();
        $players_team2 = $match->team2->players->pluck('steam_id', 'steam_name')->toArray();

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
                'id' => $match->team1->id,
                'name' => $match->team1->name,
                'tag' => $match->team1->tag,
                'flag' => 'DE',
                'players' => $players_team1
            ],
            'team2' => [
                'id' => $match->team2->id,
                'name' => $match->team2->name,
                'tag' => $match->team2->tag,
                'flag' => 'DE',
                'players' => $players_team2
            ],
            'matchid' => $match->match_number,
            'num_maps' => $match->tournament->matchup_rounds,
            'players_per_team' => 5,
            'min_players_to_ready' => 5,
            'max_rounds' => 24,
            'max_overtime_rounds' => 6,
            'vote_timeout' => 60000,
            'eventula_apistats_url' => 'https://dev.lan2play.de/api/matchmaking/40/',
            'eventula_apistats_token' => 'Bearer S0XRU0UhIExFQ0tFUiEK',
            'eventula_demo_upload_url' => 'https://dev.lan2play.de/api/matchmaking/40/demo',
            'vote_map' => 'de_inferno',
            'server_locale' => 'de'
        ];

        return response()->json($json);
    }
}
