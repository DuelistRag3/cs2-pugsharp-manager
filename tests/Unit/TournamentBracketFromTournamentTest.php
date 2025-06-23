<?php

use App\Models\Tournament;
use App\Models\Team;
use App\Models\Game;
use App\Services\TournamentRoundService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('generates bracket using tournament game results', function () {
    $tournament = Tournament::create([
        'name' => 'Test Cup',
        'max_teams' => 4,
    ]);

    $teamA = $tournament->teams()->create(['name' => 'Alpha']);
    $teamB = $tournament->teams()->create(['name' => 'Bravo']);
    $teamC = $tournament->teams()->create(['name' => 'Charlie']);
    $teamD = $tournament->teams()->create(['name' => 'Delta']);

    $game1 = new Game();
    $game1->team1_id = $teamD->id;
    $game1->team2_id = $teamA->id;
    $game1->status = 'completed';
    $game1->team1_score = 16;
    $game1->team2_score = 10;
    $tournament->games()->save($game1);

    $game2 = new Game();
    $game2->team1_id = $teamC->id;
    $game2->team2_id = $teamB->id;
    $game2->status = 'completed';
    $game2->team1_score = 8;
    $game2->team2_score = 16;
    $tournament->games()->save($game2);

    $bracket = TournamentRoundService::generateBracketForTournament($tournament, 1);

    expect(array_keys($bracket))->toEqual(['Halbfinale', 'Finale']);
    expect($bracket['Halbfinale'])->toContain([$teamD->name, $teamA->name])
        ->toContain([$teamC->name, $teamB->name]);
    expect($bracket['Finale'][0])->toContain($teamD->name, $teamB->name);
});
