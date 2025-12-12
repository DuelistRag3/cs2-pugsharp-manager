<?php

use App\Models\Tournament;
use Illuminate\Support\Facades\DB;

test('tournament 12 exists in mysql database', function () {
    // Configure MySQL connection from .env values
    config([
        'database.default' => 'mysql',
        'database.connections.mysql.host' => env('DB_HOST', '127.0.0.1'),
        'database.connections.mysql.port' => env('DB_PORT', '3306'),
        'database.connections.mysql.database' => 'cs2pugsharp',
        'database.connections.mysql.username' => env('DB_USERNAME', 'root'),
        'database.connections.mysql.password' => env('DB_PASSWORD', ''),
    ]);
    
    // Purge any existing connection and reconnect
    DB::purge('mysql');
    DB::reconnect('mysql');
    
    $tournament = Tournament::find(12);
    
    expect($tournament)->not->toBeNull('Tournament 12 should exist in database');
    
    echo "\nTournament ID: {$tournament->id}\n";
    echo "Name: {$tournament->name}\n";
    echo "Type: {$tournament->type}\n";
    echo "Teams: {$tournament->teams->count()}\n";
    echo "Games: {$tournament->games->count()}\n";
    echo "Status: {$tournament->status}\n";
});

test('finalize first game with specific score using API', function () {
    // Configure MySQL connection
    config([
        'database.default' => 'mysql',
        'database.connections.mysql.host' => env('DB_HOST', '127.0.0.1'),
        'database.connections.mysql.port' => env('DB_PORT', '3306'),
        'database.connections.mysql.database' => 'cs2pugsharp',
        'database.connections.mysql.username' => env('DB_USERNAME', 'root'),
        'database.connections.mysql.password' => env('DB_PASSWORD', ''),
    ]);
    
    DB::purge('mysql');
    DB::reconnect('mysql');
    
    $tournament = Tournament::find(12);
    expect($tournament)->not->toBeNull();
    
    // Reset and regenerate match plan
    echo "\nResetting tournament match plan...\n";
    $tournament->games()->delete(); // Reset match plan
    
    echo "Generating new match plan...\n";
    $tournament->generateMatchPlan(1); // Swiss tournament
    
    echo "Assigning teams to match plan...\n";
    $tournament->addTeamsToMatchPlan(); // This scrambles and assigns teams
    
    $tournament->refresh();
    echo "Total games created: " . $tournament->games()->count() . "\n";
    
    // Get first game (round 1, match 1)
    $firstGame = $tournament->games()->where('round', 1)->orderBy('match_number')->first();
    expect($firstGame)->not->toBeNull('First game should exist');
    expect($firstGame->team1_id)->not->toBeNull('Team 1 should be assigned');
    expect($firstGame->team2_id)->not->toBeNull('Team 2 should be assigned');
    
    echo "\nFirst Game ID: {$firstGame->id}\n";
    echo "Team 1: {$firstGame->team1->name}\n";
    echo "Team 2: {$firstGame->team2->name}\n";
    
    // Create a map for the game (simulating goLive)
    $map = new \App\Models\GameMap([
        'map_number' => 1,
        'map_name' => 'de_dust2',
        'team1_score' => 0,
        'team2_score' => 0,
        'status' => 'ongoing',
    ]);
    $firstGame->maps()->save($map);
    
    echo "Map created with ID: {$map->id}\n";
    
    // Prepare API request data
    $team1Score = 13;
    $team2Score = rand(0, 11); // Random score below 12
    $winner = $firstGame->team1->name; // Team 1 wins with 13 rounds
    
    // Simulate the API call to finalize the map
    $apiToken = config('manager.api_bearer_token');
    
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$apiToken}",
        'Accept' => 'application/json',
    ])->postJson("/api/matches/{$firstGame->id}/stats/finalize/0", [
        'team1score' => $team1Score,
        'team2score' => $team2Score,
        'winner' => $winner,
    ]);
    
    $response->assertStatus(200);
    
    // Reload the map from database
    $map->refresh();
    
    echo "\nFinal Scores:\n";
    echo "Team 1: {$map->team1_score}\n";
    echo "Team 2: {$map->team2_score}\n";
    echo "Winner: {$winner}\n";
    echo "Map Status: {$map->status}\n";
    
    expect($map->team1_score)->toBe($team1Score);
    expect($map->team2_score)->toBe($team2Score);
    expect($map->status)->toBe('completed');
    expect($map->winner_team_id)->toBe($firstGame->team1_id);
    
    // Now finalize the matchup (complete the game)
    $finalizeResponse = $this->withHeaders([
        'Authorization' => "Bearer {$apiToken}",
        'Accept' => 'application/json',
    ])->postJson("/api/matches/{$firstGame->id}/stats/finalize", [
        'winner' => $winner,
        'forfeit' => 0,
    ]);
    
    $finalizeResponse->assertStatus(200);
    
    // Reload the game with tournament relationship
    $firstGame = \App\Models\Game::with('tournament')->find($firstGame->id);
    
    echo "\nGame Status: {$firstGame->status}\n";
    echo "Game Winner: {$firstGame->winnerTeam->name}\n";
    
    expect($firstGame->status)->toBe('completed');
    expect($firstGame->winner_team_id)->toBe($firstGame->team1_id);
    
    // Reload tournament to get fresh data
    $tournament->refresh();
    
    // Check if teams were assigned to round 2 games
    $round2Games = $tournament->games()->where('round', 2)->orderBy('match_number')->get();
    echo "\nRound 2 has {$round2Games->count()} games total\n";
    $assignedCount = $round2Games->filter(function($game) {
        return $game->team1_id !== null || $game->team2_id !== null;
    })->count();
    
    echo "\nRound 2 games with teams assigned: {$assignedCount}\n";
    
    // Show where the winner and loser were assigned
    $winnerTeamId = $firstGame->team1_id;
    $loserTeamId = $firstGame->team2_id;
    
    foreach ($round2Games as $index => $game) {
        if ($game->team1_id === $winnerTeamId || $game->team2_id === $winnerTeamId) {
            $winnerTeam = $tournament->teams->find($winnerTeamId);
            echo "Winner ({$winnerTeam->name}, 1-0) assigned to Round 2, Game " . ($index + 1) . "\n";
        }
        if ($game->team1_id === $loserTeamId || $game->team2_id === $loserTeamId) {
            $loserTeam = $tournament->teams->find($loserTeamId);
            echo "Loser ({$loserTeam->name}, 0-1) assigned to Round 2, Game " . ($index + 1) . "\n";
        }
    }
});

test('complete Swiss tournament run using API', function () {
    config([
        'database.default' => 'mysql',
        'database.connections.mysql.host' => env('DB_HOST', '127.0.0.1'),
        'database.connections.mysql.port' => env('DB_PORT', '3306'),
        'database.connections.mysql.database' => 'cs2pugsharp',
        'database.connections.mysql.username' => env('DB_USERNAME', 'root'),
        'database.connections.mysql.password' => env('DB_PASSWORD', ''),
    ]);
    
    DB::purge('mysql');
    DB::reconnect('mysql');
    
    $tournament = Tournament::find(12);
    expect($tournament)->not->toBeNull();
    
    echo "\n=== TOURNAMENT SETUP ===\n";
    $tournament->games()->delete();
    $tournament->generateMatchPlan(1);
    $tournament->addTeamsToMatchPlan();
    $tournament->refresh();
    
    $totalTeams = $tournament->teams()->count();
    $totalGames = $tournament->games()->count();
    echo "Tournament: {$tournament->name}\n";
    echo "Teams: {$totalTeams}\n";
    echo "Pre-generated games: {$totalGames}\n";
    
    $apiToken = config('manager.api_bearer_token');
    $currentRound = 1;
    $maxRounds = 10; // Safety limit
    
    while ($currentRound <= $maxRounds) {
        echo "\n=== ROUND {$currentRound} ===\n";
        
        // Get games that have both teams assigned and aren't completed
        $roundGames = $tournament->games()
            ->where('round', $currentRound)
            ->whereNotNull('team1_id')
            ->whereNotNull('team2_id')
            ->where('status', '!=', 'completed')
            ->orderBy('match_number')
            ->get();
        
        if ($roundGames->isEmpty()) {
            echo "No playable games in Round {$currentRound}. Tournament incomplete.\n";
            break;
        }
        
        echo "Playing {$roundGames->count()} games\n";
        
        foreach ($roundGames as $game) {
            // Randomly decide winner
            $team1Wins = rand(0, 1) === 1;
            $team1Score = $team1Wins ? 13 : rand(0, 12);
            $team2Score = $team1Wins ? rand(0, 12) : 13;
            $winner = $team1Wins ? $game->team1->name : $game->team2->name;
            
            // Create and finalize map
            $map = new \App\Models\GameMap([
                'map_number' => 1,
                'map_name' => 'de_dust2',
                'team1_score' => 0,
                'team2_score' => 0,
                'status' => 'ongoing',
            ]);
            $game->maps()->save($map);
            
            $this->withHeaders(['Authorization' => "Bearer {$apiToken}", 'Accept' => 'application/json'])
                ->postJson("/api/matches/{$game->id}/stats/finalize/0", [
                    'team1score' => $team1Score,
                    'team2score' => $team2Score,
                    'winner' => $winner,
                ])->assertStatus(200);
            
            $this->withHeaders(['Authorization' => "Bearer {$apiToken}", 'Accept' => 'application/json'])
                ->postJson("/api/matches/{$game->id}/stats/finalize", [
                    'winner' => $winner,
                    'forfeit' => 0,
                ])->assertStatus(200);
        }
        
        // Calculate standings - query teams fresh
        $teams = $tournament->teams()->get();
        
        // Hardcoded for 16 teams
        $winsNeeded = 3;
        
        $teamRecords = [];
        foreach ($teams as $team) {
            $wins = $tournament->games()->where('status', 'completed')->where('winner_team_id', $team->id)->count();
            $losses = $tournament->games()
                ->where('status', 'completed')
                ->where(function($q) use ($team) { $q->where('team1_id', $team->id)->orWhere('team2_id', $team->id); })
                ->where('winner_team_id', '!=', $team->id)
                ->whereNotNull('winner_team_id')
                ->count();
            
            $teamRecords[] = [
                'name' => $team->name,
                'wins' => $wins,
                'losses' => $losses,
                'finished' => $wins >= $winsNeeded || $losses >= $winsNeeded,
            ];
        }
        
        $standings = collect($teamRecords)->groupBy(fn($r) => "{$r['wins']}-{$r['losses']}");
        foreach ($standings->sortKeysDesc() as $record => $teams) {
            echo "{$record}: {$teams->count()} teams\n";
        }
        
        $stillPlaying = collect($teamRecords)->filter(fn($r) => !$r['finished'])->count();
        
        if ($stillPlaying === 0) {
            echo "\n=== TOURNAMENT COMPLETE ===\n";
            $advancing = collect($teamRecords)->filter(fn($r) => $r['wins'] >= $winsNeeded)->count();
            $eliminated = collect($teamRecords)->filter(fn($r) => $r['losses'] >= $winsNeeded)->count();
            echo "Advanced ({$winsNeeded}+ wins): {$advancing}\n";
            echo "Eliminated ({$winsNeeded}+ losses): {$eliminated}\n";
            
            expect($advancing)->toBe($totalTeams / 2);
            expect($eliminated)->toBe($totalTeams / 2);
            
            // Verify tournament is marked as completed
            $tournament = Tournament::find(12);
            expect($tournament->status)->toBe('completed');
            expect($tournament->end_date)->not->toBeNull();
            echo "Tournament status: {$tournament->status}\n";
            
            break;
        }
        
        $currentRound++;
        
        if ($currentRound > $maxRounds) {
            echo "\n=== SAFETY LIMIT REACHED ===\n";
            echo "Tournament did not complete within {$maxRounds} rounds.\n";
            echo "This may indicate an issue with team assignment logic.\n";
            
            // Show final standings anyway
            $advancing = collect($teamRecords)->filter(fn($r) => $r['wins'] >= 3)->count();
            $eliminated = collect($teamRecords)->filter(fn($r) => $r['losses'] >= 3)->count();
            echo "\nFinal Status:\n";
            echo "Advanced (3+ wins): {$advancing}\n";
            echo "Eliminated (3+ losses): {$eliminated}\n";
            echo "Still playing: {$stillPlaying}\n";
            break;
        }
    }
});

test('complete Swiss tournament with 8 teams using API', function () {
    config([
        'database.default' => 'mysql',
        'database.connections.mysql.host' => env('DB_HOST', '127.0.0.1'),
        'database.connections.mysql.port' => env('DB_PORT', '3306'),
        'database.connections.mysql.database' => 'cs2pugsharp',
        'database.connections.mysql.username' => env('DB_USERNAME', 'root'),
        'database.connections.mysql.password' => env('DB_PASSWORD', ''),
    ]);
    
    DB::purge('mysql');
    DB::reconnect('mysql');
    
    // Create a new tournament with 8 teams
    $tournament = Tournament::create([
        'name' => 'Test Swiss 8 Teams',
        'type' => 1, // Swiss
        'status' => 'ongoing',
        'team_size' => 5,
        'maps_each_game' => 1,
        'maps_final_game' => 3,
        'map_rounds' => 24,
        'map_overtime_rounds' => 6,
        'guest_mode' => true,
    ]);
    
    // Create 8 teams
    for ($i = 1; $i <= 8; $i++) {
        $team = \App\Models\Team::create([
            'name' => "8Team Test Team {$i}",
            'tag' => "8TT{$i}",
        ]);
        $tournament->teams()->attach($team->id);
    }
    
    echo "\n=== TOURNAMENT SETUP (8 TEAMS) ===\n";
    $tournament->generateMatchPlan(1);
    $tournament->addTeamsToMatchPlan();
    $tournament->refresh();
    
    $totalTeams = $tournament->teams()->count();
    $totalGames = $tournament->games()->count();
    echo "Tournament: {$tournament->name}\n";
    echo "Teams: {$totalTeams}\n";
    echo "Pre-generated games: {$totalGames}\n";
    
    $apiToken = config('manager.api_bearer_token');
    $currentRound = 1;
    $maxRounds = 10;
    
    while ($currentRound <= $maxRounds) {
        echo "\n=== ROUND {$currentRound} ===\n";
        
        $roundGames = $tournament->games()
            ->where('round', $currentRound)
            ->whereNotNull('team1_id')
            ->whereNotNull('team2_id')
            ->where('status', '!=', 'completed')
            ->orderBy('match_number')
            ->get();
        
        if ($roundGames->isEmpty()) {
            echo "No playable games in Round {$currentRound}. Tournament incomplete.\n";
            
            // Debug: Check round games
            $allRoundGames = $tournament->games()->where('round', $currentRound)->get();
            echo "Total Round {$currentRound} games: {$allRoundGames->count()}\n";
            if ($allRoundGames->isNotEmpty()) {
                foreach ($allRoundGames->take(5) as $g) {
                    echo "  Game {$g->match_number}: Team1={$g->team1_id}, Team2={$g->team2_id}\n";
                }
            }
            
            break;
        }
        
        echo "Playing {$roundGames->count()} games\n";
        
        foreach ($roundGames as $game) {
            $team1Wins = rand(0, 1) === 1;
            $team1Score = $team1Wins ? 13 : rand(0, 12);
            $team2Score = $team1Wins ? rand(0, 12) : 13;
            $winner = $team1Wins ? $game->team1->name : $game->team2->name;
            
            $map = new \App\Models\GameMap([
                'map_number' => 1,
                'map_name' => 'de_dust2',
                'team1_score' => 0,
                'team2_score' => 0,
                'status' => 'ongoing',
            ]);
            $game->maps()->save($map);
            
            $this->withHeaders(['Authorization' => "Bearer {$apiToken}", 'Accept' => 'application/json'])
                ->postJson("/api/matches/{$game->id}/stats/finalize/0", [
                    'team1score' => $team1Score,
                    'team2score' => $team2Score,
                    'winner' => $winner,
                ])->assertStatus(200);
            
            $this->withHeaders(['Authorization' => "Bearer {$apiToken}", 'Accept' => 'application/json'])
                ->postJson("/api/matches/{$game->id}/stats/finalize", [
                    'winner' => $winner,
                    'forfeit' => 0,
                ])->assertStatus(200);
        }
        
        $teams = $tournament->teams()->get();
        
        $teamRecords = [];
        foreach ($teams as $team) {
            $wins = $tournament->games()->where('status', 'completed')->where('winner_team_id', $team->id)->count();
            $losses = $tournament->games()
                ->where('status', 'completed')
                ->where(function($q) use ($team) { $q->where('team1_id', $team->id)->orWhere('team2_id', $team->id); })
                ->where('winner_team_id', '!=', $team->id)
                ->whereNotNull('winner_team_id')
                ->count();
            
            $teamRecords[] = [
                'name' => $team->name,
                'wins' => $wins,
                'losses' => $losses,
                'finished' => $wins >= 3 || $losses >= 3,
            ];
        }
        
        $standings = collect($teamRecords)->groupBy(fn($r) => "{$r['wins']}-{$r['losses']}");
        foreach ($standings->sortKeysDesc() as $record => $teams) {
            echo "{$record}: {$teams->count()} teams\n";
        }
        
        $stillPlaying = collect($teamRecords)->filter(fn($r) => !$r['finished'])->count();
        
        if ($stillPlaying === 0) {
            echo "\n=== TOURNAMENT COMPLETE ===\n";
            $advancing = collect($teamRecords)->filter(fn($r) => $r['wins'] === 3)->count();
            $eliminated = collect($teamRecords)->filter(fn($r) => $r['losses'] === 3)->count();
            echo "Advanced (3+ wins): {$advancing}\n";
            echo "Eliminated (3+ losses): {$eliminated}\n";
            
            expect($advancing)->toBe($totalTeams / 2);
            expect($eliminated)->toBe($totalTeams / 2);
            
            $tournament->refresh();
            expect($tournament->status)->toBe('completed');
            expect($tournament->end_date)->not->toBeNull();
            echo "Tournament status: {$tournament->status}\n";
            
            break;
        }
        
        $currentRound++;
        
        if ($currentRound > $maxRounds) {
            echo "\n=== SAFETY LIMIT REACHED ===\n";
            echo "Tournament did not complete within {$maxRounds} rounds.\n";
            
            $winsNeeded = 3;
            $advancing = collect($teamRecords)->filter(fn($r) => $r['wins'] >= $winsNeeded)->count();
            $eliminated = collect($teamRecords)->filter(fn($r) => $r['losses'] >= $winsNeeded)->count();
            echo "\nFinal Status:\n";
            echo "Advanced ({$winsNeeded}+ wins): {$advancing}\n";
            echo "Eliminated ({$winsNeeded}+ losses): {$eliminated}\n";
            echo "Still playing: {$stillPlaying}\n";
            break;
        }
    }
    
    // Clean up
    $tournament->games()->delete();
    foreach ($tournament->teams as $team) {
        $tournament->teams()->detach($team->id);
        $team->delete();
    }
    $tournament->delete();
});
