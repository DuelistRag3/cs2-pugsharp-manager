<?php

use App\Services\TournamentRoundService;

it('generates correct round names', function () {
    $rounds = TournamentRoundService::generateRounds(16);
    expect($rounds)->toEqual([
        'Achtelfinale',
        'Viertelfinale',
        'Halbfinale',
        'Finale',
    ]);
});

it('generates bracket pairings', function () {
    $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
    $bracket = TournamentRoundService::generateBracket($teams);

    expect(array_keys($bracket))->toEqual(['Halbfinale', 'Finale']);
    expect($bracket['Halbfinale'])->toEqual([
        ['Team 1', 'Team 2'],
        ['Team 3', 'Team 4'],
    ]);
});
