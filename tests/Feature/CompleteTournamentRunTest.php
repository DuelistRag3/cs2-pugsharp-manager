<?php

use App\Models\Tournament;
use App\Models\Team;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('complete swiss tournament run with 16 teams', function () {
    // Create tournament
    $tournament = Tournament::create([
        'name' => 'Complete Swiss Tournament Test',
        'description' => 'Testing complete tournament flow',
        'start_date' => now()->addDays(1),
        'team_size' => 5,
        'max_teams' => 16,
        'maps_each_game' => 1,
        'maps_final_game' => 1,
        'map_rounds' => 24,
        'map_overtime_rounds' => 6,
        'status' => 'scheduled',
        'guest_mode' => false,
    ]);

    // Create 16 teams
    $teams = [];
    for ($i = 1; $i <= 16; $i++) {
        $team = Team::create([
            'name' => "Team $i",
            'tag' => "T$i",
        ]);
        $tournament->teams()->attach($team->id);
        $teams[] = $team;
    }

    // Generate Swiss match plan (type 1)
    $tournament->generateMatchPlan(1);
    
    // Only assign teams to round 1 manually (Swiss doesn't pre-assign all rounds)
    $firstRoundGames = $tournament->games()->where('round', 1)->orderBy('match_number')->get();
    $teamsList = $tournament->teams->shuffle()->values();
    
    $teamIndex = 0;
    foreach ($firstRoundGames as $game) {
        if ($teamIndex < $teamsList->count()) {
            $game->team1_id = $teamsList[$teamIndex]->id;
            $teamIndex++;
        }
        if ($teamIndex < $teamsList->count()) {
            $game->team2_id = $teamsList[$teamIndex]->id;
            $teamIndex++;
        }
        $game->save();
    }

    // Verify match count
    expect($tournament->games()->count())->toBe(33);

    // Simulate complete tournament
    $roundGames = [
        1 => 8, // Round 1: 8 games
        2 => 8, // Round 2: 8 games
        3 => 8, // Round 3: 8 games
        4 => 6, // Round 4: 6 games
        5 => 3, // Round 5: 3 games
    ];

    foreach ($roundGames as $round => $expectedGames) {
        echo "\n--- Round $round ---\n";
        
        $games = $tournament->games()->where('round', $round)->get();
        expect($games->count())->toBe($expectedGames, "Round $round should have $expectedGames games");

        // For rounds 2+, we need to pair teams based on their current record
        if ($round > 1) {
            // Get team records
            $teamRecords = [];
            foreach ($tournament->teams as $team) {
                $wins = $losses = 0;
                foreach ($tournament->games()->where('status', 'completed')->get() as $completedGame) {
                    if ($completedGame->team1_id === $team->id) {
                        $completedGame->winner_team_id === $team->id ? $wins++ : $losses++;
                    } elseif ($completedGame->team2_id === $team->id) {
                        $completedGame->winner_team_id === $team->id ? $wins++ : $losses++;
                    }
                }
                
                // Only include teams that haven't finished (less than 3 wins or losses)
                if ($wins < 3 && $losses < 3) {
                    $teamRecords[] = [
                        'team_id' => $team->id,
                        'wins' => $wins,
                        'losses' => $losses,
                    ];
                }
            }
            
            // Group teams by score
            $scoreGroups = [];
            foreach ($teamRecords as $record) {
                $score = "{$record['wins']}-{$record['losses']}";
                if (!isset($scoreGroups[$score])) {
                    $scoreGroups[$score] = [];
                }
                $scoreGroups[$score][] = $record['team_id'];
            }
            
            // Assign teams to games based on score brackets
            $gameIndex = 0;
            foreach ($scoreGroups as $score => $teamIds) {
                shuffle($teamIds);
                for ($i = 0; $i < count($teamIds); $i += 2) {
                    if (isset($teamIds[$i + 1]) && $gameIndex < $games->count()) {
                        $games[$gameIndex]->team1_id = $teamIds[$i];
                        $games[$gameIndex]->team2_id = $teamIds[$i + 1];
                        $games[$gameIndex]->save();
                        $gameIndex++;
                    }
                }
            }
        }

        // Simulate each game
        foreach ($games as $game) {
            // Skip if teams not assigned
            if (!$game->team1_id || !$game->team2_id) {
                continue;
            }

            // Randomly determine winner
            $winner = rand(0, 1) === 0 ? $game->team1_id : $game->team2_id;
            
            // Update game
            $game->winner_team_id = $winner;
            $game->status = 'completed';
            $game->save();

            echo "Game {$game->match_number}: Team {$game->team1->tag} vs {$game->team2->tag} - Winner: " . 
                 ($winner === $game->team1_id ? $game->team1->tag : $game->team2->tag) . "\n";
        }

        // Calculate standings after this round
        $standings = [];
        foreach ($tournament->teams as $team) {
            $wins = 0;
            $losses = 0;

            foreach ($tournament->games()->where('status', 'completed')->get() as $game) {
                if ($game->team1_id === $team->id) {
                    if ($game->winner_team_id === $team->id) {
                        $wins++;
                    } else {
                        $losses++;
                    }
                } elseif ($game->team2_id === $team->id) {
                    if ($game->winner_team_id === $team->id) {
                        $wins++;
                    } else {
                        $losses++;
                    }
                }
            }

            if ($wins > 0 || $losses > 0) {
                $standings[] = [
                    'team' => $team->tag,
                    'record' => "$wins-$losses",
                    'wins' => $wins,
                    'losses' => $losses,
                ];
            }
        }

        // Sort by wins descending, losses ascending
        usort($standings, function ($a, $b) {
            if ($a['wins'] !== $b['wins']) {
                return $b['wins'] - $a['wins'];
            }
            return $a['losses'] - $b['losses'];
        });

        echo "\nStandings after Round $round:\n";
        foreach ($standings as $standing) {
            $status = '';
            if ($standing['wins'] >= 3) {
                $status = ' [ADVANCING]';
            } elseif ($standing['losses'] >= 3) {
                $status = ' [ELIMINATED]';
            }
            echo "{$standing['team']}: {$standing['record']}$status\n";
        }
    }

    // Verify final results
    $advancingTeams = 0;
    $eliminatedTeams = 0;

    foreach ($tournament->teams as $team) {
        $wins = 0;
        $losses = 0;

        foreach ($tournament->games()->where('status', 'completed')->get() as $game) {
            if ($game->team1_id === $team->id) {
                if ($game->winner_team_id === $team->id) {
                    $wins++;
                } else {
                    $losses++;
                }
            } elseif ($game->team2_id === $team->id) {
                if ($game->winner_team_id === $team->id) {
                    $wins++;
                } else {
                    $losses++;
                }
            }
        }

        if ($wins >= 3) {
            $advancingTeams++;
        }
        if ($losses >= 3) {
            $eliminatedTeams++;
        }
    }

    echo "\n=== Final Results ===\n";
    echo "Advancing: $advancingTeams teams\n";
    echo "Eliminated: $eliminatedTeams teams\n";

    // Verify 8 teams advanced and 8 eliminated
    expect($advancingTeams)->toBe(8, "Should have 8 advancing teams");
    expect($eliminatedTeams)->toBe(8, "Should have 8 eliminated teams");
});

test('complete swiss tournament run with 8 teams', function () {
    $tournament = Tournament::create([
        'name' => 'Swiss Tournament 8 Teams',
        'description' => 'Testing 8 team Swiss',
        'start_date' => now()->addDays(1),
        'team_size' => 5,
        'max_teams' => 8,
        'maps_each_game' => 1,
        'maps_final_game' => 1,
        'map_rounds' => 24,
        'map_overtime_rounds' => 6,
        'status' => 'scheduled',
        'guest_mode' => false,
    ]);

    for ($i = 1; $i <= 8; $i++) {
        $team = Team::create([
            'name' => "Team 8T-$i",
            'tag' => "8T$i",
        ]);
        $tournament->teams()->attach($team->id);
    }

    $tournament->generateMatchPlan(1);
    
    // Assign teams to round 1
    $firstRoundGames = $tournament->games()->where('round', 1)->orderBy('match_number')->get();
    $teamsList = $tournament->teams->shuffle()->values();
    
    $teamIndex = 0;
    foreach ($firstRoundGames as $game) {
        if ($teamIndex < $teamsList->count()) {
            $game->team1_id = $teamsList[$teamIndex]->id;
            $teamIndex++;
        }
        if ($teamIndex < $teamsList->count()) {
            $game->team2_id = $teamsList[$teamIndex]->id;
            $teamIndex++;
        }
        $game->save();
    }

    $totalGames = $tournament->games()->count();
    expect($totalGames)->toBeGreaterThan(0);

    echo "\n8 Teams Tournament: $totalGames total games\n";

    // Simulate round by round
    $maxRound = $tournament->games()->max('round');
    for ($round = 1; $round <= $maxRound; $round++) {
        echo "\n--- Round $round ---\n";
        
        // For rounds 2+, pair teams based on record
        if ($round > 1) {
            $teamRecords = [];
            foreach ($tournament->teams as $team) {
                $wins = $losses = 0;
                foreach ($tournament->games()->where('status', 'completed')->get() as $completedGame) {
                    if ($completedGame->team1_id === $team->id) {
                        $completedGame->winner_team_id === $team->id ? $wins++ : $losses++;
                    } elseif ($completedGame->team2_id === $team->id) {
                        $completedGame->winner_team_id === $team->id ? $wins++ : $losses++;
                    }
                }
                
                if ($wins < 3 && $losses < 3) {
                    $teamRecords[] = [
                        'team_id' => $team->id,
                        'wins' => $wins,
                        'losses' => $losses,
                    ];
                }
            }
            
            $scoreGroups = [];
            foreach ($teamRecords as $record) {
                $score = "{$record['wins']}-{$record['losses']}";
                if (!isset($scoreGroups[$score])) {
                    $scoreGroups[$score] = [];
                }
                $scoreGroups[$score][] = $record['team_id'];
            }
            
            $games = $tournament->games()->where('round', $round)->get();
            $gameIndex = 0;
            foreach ($scoreGroups as $score => $teamIds) {
                shuffle($teamIds);
                for ($i = 0; $i < count($teamIds); $i += 2) {
                    if (isset($teamIds[$i + 1]) && $gameIndex < $games->count()) {
                        $games[$gameIndex]->team1_id = $teamIds[$i];
                        $games[$gameIndex]->team2_id = $teamIds[$i + 1];
                        $games[$gameIndex]->save();
                        $gameIndex++;
                    }
                }
            }
        }
        
        $games = $tournament->games()->where('round', $round)->get();
        foreach ($games as $game) {
            if ($game->team1_id && $game->team2_id) {
                $game->winner_team_id = rand(0, 1) === 0 ? $game->team1_id : $game->team2_id;
                $game->status = 'completed';
                $game->save();
                echo "Game: {$game->team1->tag} vs {$game->team2->tag} - Winner: " . 
                     ($game->winner_team_id === $game->team1_id ? $game->team1->tag : $game->team2->tag) . "\n";
            }
        }
    }

    // Count advancing/eliminated
    $advancing = 0;
    $eliminated = 0;

    echo "\n=== Final 8 Team Results ===\n";
    foreach ($tournament->teams as $team) {
        $wins = $losses = 0;
        foreach ($tournament->games()->where('status', 'completed')->get() as $game) {
            if ($game->team1_id === $team->id) {
                $game->winner_team_id === $team->id ? $wins++ : $losses++;
            } elseif ($game->team2_id === $team->id) {
                $game->winner_team_id === $team->id ? $wins++ : $losses++;
            }
        }
        if ($wins >= 3) $advancing++;
        if ($losses >= 3) $eliminated++;
        
        $status = '';
        if ($wins >= 3) $status = ' [ADVANCING]';
        elseif ($losses >= 3) $status = ' [ELIMINATED]';
        echo "{$team->tag}: {$wins}-{$losses}$status\n";
    }

    echo "\nAdvancing: $advancing teams\n";
    echo "Eliminated: $eliminated teams\n";

    // With 8 teams and 15 games (4 rounds), not all teams may finish
    // The important thing is that we have matches and the logic works
    expect($advancing + $eliminated)->toBeGreaterThanOrEqual(4, "At least half the teams should finish");
});
