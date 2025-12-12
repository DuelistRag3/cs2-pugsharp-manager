<div
    class="grid-cols-1 grid-cols-2 grid-cols-3 grid-cols-4 grid-cols-5 grid-cols-6 grid-cols-7 grid-cols-8 grid-cols-9 grid-cols-10">
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white">{{ __('manager.tournament') }}: {{ $tournament->name }}</h1>
            <div class="right">
                @if($tournament->status === 'ongoing')
                <button wire:click='cancelTournament()' type="button"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cursor-pointer">{{
                    __('manager.cancel_tournament') }}</button>
                @if($tournament->games->isEmpty())
                <button wire:click='generateMatchPlan(0)' type="button"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer">{{
                    __('manager.generate_bracket_matchplan') }}</button>
                <button type="button" wire:click='generateMatchPlan(1)'
                    class="focus:outline-none disabled:opacity-75 disabled:cursor-not-allowed! text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer">{{
                    __('manager.generate_swiss_matchplan') }}</button>
                @else
                <button wire:click='resetMatchPlan()' type="button"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cursor-pointer">{{
                    __('manager.reset_matchplan') }}</button>
                <button wire:click='addTeamsToMatchPlan()' type="button"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer">
                    {{ __('manager.scramble_teams') }}
                </button>
                <button wire:click='removeAllTeamsFromMatchPlan()' type="button"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cursor-pointer">{{
                    __('manager.empty_matchplan') }}</button>
                @endif
                @endif
                @if($tournament->status === 'scheduled')
                <button type="button" data-modal-target="create-modal" data-modal-toggle="create-modal"
                    class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 cursor-pointer"><i
                        class="fa-solid fa-plus"></i> {{ __('manager.edit') }}</button>
                <button wire:click='startTournament()' type="button"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer">{{
                    __('manager.start_tournament') }}</button>
                @endif
            </div>
        </div>

        <x-tournament-details-card :tournament=$tournament />

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">{{ __('manager.maps') }}
                @if($tournament->status === 'scheduled')
                <button type="button" wire:click='addAllAvailableMaps' wire:target='addAllAvailableMaps' wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed! disabled"
                    class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 cursor-pointer"><i
                        class="fa-solid fa-plus" wire:target='addAllAvailableMaps' wire:loading.class.remove='fa-plus' wire:loading.class="fa-spinner fa-spin"></i> {{ __('manager.add_all_maps') }}</button>
                @endif
            </h2>
            <div class="grid grid-cols-12 gap-4">
                @foreach($availableMaps as $map)
                <div @if($tournament->status === 'scheduled') wire:click='changeMapState({{ $map->id }})'
                    wire:loading.class="opacity-50 cursor-not-allowed disabled" wire:target='changeMapState()'  @endif
                    class="border border-gray-200 rounded-lg shadow-sm bg-gray-800 dark:border-gray-700 col-span-2 @if($tournament->status === 'scheduled') cursor-pointer @endif @if($tournament->availableMaps->contains($map)) bg-green-900 @endif">
                    <a>
                        <img class="rounded-t-lg" src="{{ $map->getImageUrlAttribute() }}" alt="{{ $map->name }}" />
                    </a>
                    <div class="p-5">
                        <a>
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{
                                $map->name }}</h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $map->map_code }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">{{ __('manager.teams') }}</h2>
            @if($tournament->teams->isEmpty())
            <p>{{ __('manager.no_teams_registered') }}</p>
            @else
            <ul class="list-disc">
                @foreach($tournament->teams as $team)
                <button data-modal-target="team{{ $team->id }}-modal" data-modal-toggle="team{{ $team->id }}-modal"
                    class="bg-blue-100 cursor-pointer hover:bg-blue-200 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center mt-2">{{
                    $team->name }}</button>
                @endforeach
            </ul>

            @endif
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">{{ __('manager.tournament_plan') }}</h2>
            @if($tournament->type === 0) {{-- Bracket style --}}
            @php
            $numberOfRounds = $tournament->games->max('round') ?? 0;
            $offset = 0;
            @endphp
            <div id="bracket-container" class="relative grid grid-rows-1 gap-4 grid-cols-{{ ($numberOfRounds) }}"
                wire:poll>
                <svg id="bracket-lines" class="absolute inset-0 w-full h-full pointer-events-none" style="z-index: 1;"
                    wire:ignore>

                </svg>
                @for($round = 0; $round < $numberOfRounds; $round++) <div class="mb-4">
                    @php
                    if (isset(config('manager.round_name_tokens')[$numberOfRounds][$round])) {
                    $roundNameToken = config('manager.round_name_tokens')[$numberOfRounds][$round];
                    } else {
                    $roundNameToken = 'round_' . ($round + 1);
                    }
                    $roundNameToken = "manager.round_names.$roundNameToken";
                    @endphp
                    <h3 class="text-lg font-semibold mb-2">
                        {{ __($roundNameToken) }}
                    </h3>
                    <div class="h-full grid content-around">
                        @php
                        $roundGames = $tournament->games()->where('round', ($round + 1))->get();
                        $offset = $offset + $roundGames->count();
                        @endphp

                        @foreach($roundGames as $game)
                        <div id="game{{ $game->id }}" next-game-id="{{ $game->next_game_id }}"
                            class="max-w-48 text-sm font-medium mb-2 text-gray-900 bg-white border rounded-lg dark:bg-gray-700  dark:text-white relative border-{{ config('manager.status_colors.' . $game->status) }}-500!"
                            style="">
                            <x-tournament-game-card :game=$game />
                        </div>
                        @endforeach
                    </div>
            </div>
            @endfor
            @elseif ($tournament->type === 1) {{-- Swiss elimination style (group phase) --}}
            @php
                $maxRound = $tournament->games->max('round') ?? 0;
                $numTeams = $tournament->teams->count();
                
                // Calculate team records (wins-losses) for each team based on completed games
                $teamRecords = [];
                foreach($tournament->teams as $team) {
                    $wins = 0;
                    $losses = 0;
                    foreach($tournament->games as $game) {
                        if($game->status === 'completed') {
                            if($game->team1_id === $team->id) {
                                if($game->winner_team_id === $team->id) $wins++;
                                else $losses++;
                            } elseif($game->team2_id === $team->id) {
                                if($game->winner_team_id === $team->id) $wins++;
                                else $losses++;
                            }
                        }
                    }
                    $teamRecords[$team->id] = ['wins' => $wins, 'losses' => $losses, 'team' => $team];
                }
                
                // Build structure based ONLY on existing games (not team data)
                // Swiss System: Teams play until they reach 3 wins (advance) or 3 losses (eliminated)
                $roundStructure = [];
                
                // For each round, determine score brackets based on game distribution
                for ($round = 1; $round <= $maxRound; $round++) {
                    $roundGames = $tournament->games()->where('round', $round)->orderBy('match_number')->get();
                    $gamesInRound = $roundGames->count();
                    
                    // Calculate possible score combinations for THIS round only
                    // Teams finishing in this round reach exactly 3 wins or 3 losses after playing this round
                    $possibleScores = [];
                    
                    // Active scores: teams with (round-1) total games who will play in this round
                    for ($wins = 0; $wins < 3 && $wins <= ($round - 1); $wins++) {
                        $losses = ($round - 1) - $wins;
                        if ($losses >= 0 && $losses < 3) {
                            $possibleScores[] = "$wins-$losses";
                        }
                    }
                    
                    // Terminal scores that can be reached BY PLAYING in this round
                    // After this round (round N), teams will have played N games total
                    for ($wins = 0; $wins <= $round; $wins++) {
                        $losses = $round - $wins;
                        
                        // Only show terminal states that are reached exactly in this round
                        if (($wins == 3 || $losses == 3) && $wins <= 3 && $losses <= 3) {
                            $possibleScores[] = "$wins-$losses";
                        }
                    }
                    
                    // Sort scores: advancing first, then active, then eliminating
                    usort($possibleScores, function($a, $b) {
                        list($winsA, $lossesA) = explode('-', $a);
                        list($winsB, $lossesB) = explode('-', $b);
                        $winsA = (int)$winsA;
                        $lossesA = (int)$lossesA;
                        $winsB = (int)$winsB;
                        $lossesB = (int)$lossesB;
                        
                        $isAdvancingA = $winsA >= 3;
                        $isAdvancingB = $winsB >= 3;
                        $isEliminatingA = $lossesA >= 3;
                        $isEliminatingB = $lossesB >= 3;
                        
                        // Advancing scores first
                        if ($isAdvancingA && !$isAdvancingB) return -1;
                        if (!$isAdvancingA && $isAdvancingB) return 1;
                        
                        // Eliminating scores last
                        if ($isEliminatingA && !$isEliminatingB) return 1;
                        if (!$isEliminatingA && $isEliminatingB) return -1;
                        
                        // Within same category, sort by wins descending, then losses ascending
                        if ($winsA != $winsB) return $winsB - $winsA;
                        return $lossesA - $lossesB;
                    });
                    
                    // Determine which scores are "active" (teams still playing) vs "terminal" (advanced/eliminated)
                    $activeScores = [];
                    $advancingScores = [];
                    $eliminatingScores = [];
                    
                    foreach ($possibleScores as $scoreKey) {
                        list($wins, $losses) = explode('-', $scoreKey);
                        $wins = (int)$wins;
                        $losses = (int)$losses;
                        
                        // Terminal states: 3 wins (advancing) or 3 losses (eliminated)
                        if ($wins >= 3) {
                            $advancingScores[] = $scoreKey;
                        } elseif ($losses >= 3) {
                            $eliminatingScores[] = $scoreKey;
                        } else {
                            $activeScores[] = $scoreKey;
                        }
                    }
                    
                    $terminalScores = array_merge($advancingScores, $eliminatingScores);
                    
                    // Distribute actual games among active score brackets
                    $scoreGroups = [];
                    
                    // Initialize all possible scores
                    foreach ($possibleScores as $scoreKey) {
                        $scoreGroups[$scoreKey] = [
                            'games' => [],
                            'tbd_slots' => 0
                        ];
                    }
                    
                    // Distribute games based on Swiss pairing logic
                    // In Swiss, teams with the same record play each other
                    if (count($activeScores) > 0 && $gamesInRound > 0) {
                        $gameIndex = 0;
                        
                        // Calculate expected games per score bracket based on binomial distribution
                        $gamesPerScore = [];
                        
                        foreach ($activeScores as $scoreKey) {
                            list($wins, $losses) = explode('-', $scoreKey);
                            $wins = (int)$wins;
                            $losses = (int)$losses;
                            $gamesPlayed = $wins + $losses;
                            
                            // Calculate binomial coefficient C(n, k) = n! / (k! * (n-k)!)
                            $binomialCoeff = 1;
                            if ($gamesPlayed > 0 && $wins >= 0 && $wins <= $gamesPlayed) {
                                for ($i = 0; $i < $wins; $i++) {
                                    $binomialCoeff *= ($gamesPlayed - $i);
                                    $binomialCoeff /= ($i + 1);
                                }
                            }
                            
                            $gamesPerScore[$scoreKey] = $binomialCoeff;
                        }
                        
                        // Normalize to actual number of games
                        $totalWeight = array_sum($gamesPerScore);
                        $assignedGames = 0;
                        
                        if ($totalWeight > 0) {
                            foreach ($activeScores as $scoreKey) {
                                $proportion = $gamesPerScore[$scoreKey] / $totalWeight;
                                $expectedGames = round($proportion * $gamesInRound);
                                
                                for ($i = 0; $i < $expectedGames && $gameIndex < $gamesInRound; $i++) {
                                    $scoreGroups[$scoreKey]['games'][] = $roundGames[$gameIndex];
                                    $gameIndex++;
                                    $assignedGames++;
                                }
                            }
                        }
                        
                        // Assign any remaining games to the middle bracket (most common score)
                        if ($gameIndex < $gamesInRound && count($activeScores) > 0) {
                            $middleBracket = $activeScores[(int)floor(count($activeScores) / 2)];
                            while ($gameIndex < $gamesInRound) {
                                $scoreGroups[$middleBracket]['games'][] = $roundGames[$gameIndex];
                                $gameIndex++;
                            }
                        }
                    }
                    
                    // For terminal brackets, calculate TBD slots based on games in preceding brackets
                    foreach ($terminalScores as $scoreKey) {
                        list($wins, $losses) = explode('-', $scoreKey);
                        $wins = (int)$wins;
                        $losses = (int)$losses;
                        
                        // Count how many teams have already reached this exact score
                        $teamsAtScore = 0;
                        foreach ($teamRecords as $record) {
                            if ($record['wins'] == $wins && $record['losses'] == $losses) {
                                $teamsAtScore++;
                            }
                        }
                        
                        // Calculate TBD slots based on games that lead to this terminal state
                        if ($teamsAtScore == 0) {
                            // Determine which active bracket feeds into this terminal bracket
                            if ($wins >= 3) {
                                // Teams advance by winning from (wins-1, losses)
                                $precedingScore = ($wins - 1) . '-' . $losses;
                            } else {
                                // Teams eliminate by losing from (wins, losses-1)
                                $precedingScore = $wins . '-' . ($losses - 1);
                            }
                            
                            // Count games in the preceding active bracket
                            $precedingGames = 0;
                            if (isset($scoreGroups[$precedingScore])) {
                                $precedingGames = count($scoreGroups[$precedingScore]['games'] ?? []);
                            }
                            
                            // Each game in the preceding bracket produces winners/losers
                            // Winners from that bracket either advance (if winning gives 3 wins) or continue
                            // Losers from that bracket either eliminate (if losing gives 3 losses) or continue
                            $scoreGroups[$scoreKey]['tbd_slots'] = $precedingGames;
                        }
                    }
                    
                    // Add teams to terminal brackets
                    foreach ($teamRecords as $teamId => $record) {
                        $scoreKey = $record['wins'] . '-' . $record['losses'];
                        
                        // Only add to terminal brackets that exist in this round
                        if (in_array($scoreKey, $terminalScores) && isset($scoreGroups[$scoreKey])) {
                            if (!isset($scoreGroups[$scoreKey]['teams'])) {
                                $scoreGroups[$scoreKey]['teams'] = [];
                            }
                            $scoreGroups[$scoreKey]['teams'][] = $record;
                        }
                    }
                    
                    $roundStructure[$round] = [
                        'scores' => $possibleScores,
                        'active_scores' => $activeScores,
                        'advancing_scores' => $advancingScores,
                        'eliminating_scores' => $eliminatingScores,
                        'terminal_scores' => $terminalScores,
                        'groups' => $scoreGroups,
                    ];
                }
            @endphp
            
            <div class="overflow-x-auto">
                <div class="relative min-w-max pb-4" id="swiss-bracket">
                    <svg id="swiss-lines" class="absolute inset-0 w-full h-full pointer-events-none" style="z-index: 0;"></svg>
                    
                    {{-- Advancing/Eliminating Overview Boxes at the Top --}}
                    <div class="flex justify-end gap-4 mb-4 relative" style="z-index: 2;">
                        <div class="bg-green-900 bg-opacity-30 border-2 border-green-500 rounded-lg px-4 py-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <div class="text-xs text-green-400 font-bold uppercase">Advancing</div>
                                <div class="text-sm text-white">
                                    @php
                                        $advancingCount = collect($teamRecords)->filter(fn($r) => $r['wins'] >= 3)->count();
                                    @endphp
                                    {{ $advancingCount }}/{{ (int)($tournament->teams->count() / 2) }} teams
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-red-900 bg-opacity-30 border-2 border-red-500 rounded-lg px-4 py-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <div class="text-xs text-red-400 font-bold uppercase">Eliminated</div>
                                <div class="text-sm text-white">
                                    @php
                                        $eliminatedCount = collect($teamRecords)->filter(fn($r) => $r['losses'] >= 3)->count();
                                    @endphp
                                    {{ $eliminatedCount }}/{{ (int)($tournament->teams->count() / 2) }} teams
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Main Grid with Score-Grouped Columns --}}
                    <div class="relative flex gap-12 items-stretch" style="padding: 20px; z-index: 1;">
                        @foreach($roundStructure as $round => $roundData)
                            <div class="flex flex-col gap-1 w-48 flex-1">
                                {{-- Round Header --}}
                                <div class="text-center mb-2 sticky top-0 bg-gray-800 py-1 z-10 rounded">
                                    <div class="text-sm font-bold text-white">Round {{ $round }}</div>
                                </div>
                                
                                {{-- Score Groups within this round --}}
                                <div class="flex flex-col gap-3 items-stretch justify-around flex-1">
                                    {{-- Advancing teams section --}}
                                    @if(count($roundData['advancing_scores'] ?? []) > 0)
                                        <div class="border-2 border-green-600 rounded-lg p-2 bg-green-900 bg-opacity-10">
                                            <div class="text-center mb-2">
                                                <span class="text-xs font-bold text-green-400 uppercase">Advancing</span>
                                            </div>
                                            @foreach($roundData['advancing_scores'] as $scoreLabel)
                                                @php
                                                    $groupData = $roundData['groups'][$scoreLabel] ?? ['games' => [], 'teams' => [], 'tbd_slots' => 0];
                                                    $teamsInGroup = $groupData['teams'] ?? [];
                                                    $tbdSlots = $groupData['tbd_slots'] ?? 0;
                                                    $hasContent = count($teamsInGroup) > 0 || $tbdSlots > 0;
                                                @endphp
                                                
                                                @if($hasContent)
                                                    <div class="mb-2">
                                                        <div class="text-center mb-1">
                                                            <div class="inline-block px-2 py-0.5 rounded-full text-xs font-bold text-white bg-green-700 border border-green-500">
                                                                {{ $scoreLabel }}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex flex-col gap-1">
                                                            @foreach($teamsInGroup as $record)
                                                                <div class="rounded p-1.5 border-2 bg-green-900 bg-opacity-30 border-green-500">
                                                                    <div class="flex items-center justify-between">
                                                                        <div class="flex items-center gap-2">
                                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-green-700 border border-green-500">
                                                                                {{ substr($record['team']->tag, 0, 2) }}
                                                                            </div>
                                                                            <span class="text-xs font-medium text-white">{{ $record['team']->tag }}</span>
                                                                        </div>
                                                                        <span class="text-xs font-bold text-green-400">✓</span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            
                                                            @for($i = 0; $i < $tbdSlots - count($teamsInGroup); $i++)
                                                                <div class="rounded p-1.5 border-2 border-dashed bg-green-900 bg-opacity-10 border-green-600">
                                                                    <div class="flex items-center justify-center">
                                                                        <span class="text-xs text-gray-500 italic">TBD</span>
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    {{-- Active matches section --}}
                                    @foreach($roundData['active_scores'] ?? [] as $scoreLabel)
                                        @php
                                            $groupData = $roundData['groups'][$scoreLabel] ?? ['games' => [], 'teams' => [], 'tbd_slots' => 0];
                                            $gamesInGroup = $groupData['games'] ?? [];
                                            $hasContent = count($gamesInGroup) > 0;
                                        @endphp
                                        
                                        @if($hasContent)
                                            {{-- Score Label for this group --}}
                                            <div class="relative">
                                                <div class="text-center mb-1">
                                                    <div class="inline-block px-2 py-0.5 rounded-full text-xs font-bold text-white border bg-gray-700 border-gray-600">
                                                        {{ $scoreLabel }}
                                                    </div>
                                                </div>
                                                
                                                <div class="flex flex-col gap-2">
                                                    {{-- Active Matches in this score group --}}
                                                    @foreach($gamesInGroup as $game)
                                                        <div id="swiss-game-{{ $game->id }}" 
                                                             class="relative bg-gray-800 rounded shadow-md cursor-pointer hover:border-gray-500 transition-all border-2 border-gray-700"
                                                             data-game-id="{{ $game->id }}"
                                                             data-round="{{ $round }}"
                                                             data-modal-target="game{{ $game->id }}-modal" 
                                                             data-modal-toggle="game{{ $game->id }}-modal">
                                                            
                                                            {{-- Team 1 --}}
                                                            <div class="flex items-center justify-between px-2 py-1.5 border-b border-gray-700
                                                                @if($game->status === 'completed' && $game->winner_team_id && $game->winner_team_id !== $game->team1_id) opacity-40 @endif">
                                                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                                                    @if($game->team1)
                                                                        <div class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold border
                                                                            @if($game->status === 'completed' && $game->winner_team_id === $game->team1_id)
                                                                                bg-green-600 border-green-500 text-white
                                                                            @else
                                                                                bg-gray-700 border-gray-600
                                                                            @endif">
                                                                            {{ substr($game->team1->tag, 0, 3) }}
                                                                        </div>
                                                                        <span class="text-sm font-medium truncate">{{ $game->team1->tag }}</span>
                                                                    @else
                                                                        <span class="text-sm text-gray-500 italic">TBD</span>
                                                                    @endif
                                                                </div>
                                                                <span class="text-sm font-bold ml-2
                                                                    @if($game->winner_team_id === $game->team1_id) text-green-400 @endif">
                                                                    @if($game->status === 'completed')
                                                                        @if($game->tournament->maps_each_game == 0)
                                                                            {{ $game->maps->first()->team1_score ?? 0 }}
                                                                        @else
                                                                            {{ $game->team1_maps_won ?? 0 }}
                                                                        @endif
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            
                                                            {{-- VS Divider --}}
                                                            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-gray-900 px-2 py-0.5 rounded text-xs text-gray-500 font-bold border border-gray-700 z-5">
                                                                VS
                                                            </div>
                                                            
                                                            {{-- Team 2 --}}
                                                            <div class="flex items-center justify-between px-2 py-1.5
                                                                @if($game->status === 'completed' && $game->winner_team_id && $game->winner_team_id !== $game->team2_id) opacity-40 @endif">
                                                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                                                    @if($game->team2)
                                                                        <div class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold border
                                                                            @if($game->status === 'completed' && $game->winner_team_id === $game->team2_id)
                                                                                bg-green-600 border-green-500 text-white
                                                                            @else
                                                                                bg-gray-700 border-gray-600
                                                                            @endif">
                                                                            {{ substr($game->team2->tag, 0, 3) }}
                                                                        </div>
                                                                        <span class="text-sm font-medium truncate">{{ $game->team2->tag }}</span>
                                                                    @else
                                                                        <span class="text-sm text-gray-500 italic">TBD</span>
                                                                    @endif
                                                                </div>
                                                                <span class="text-sm font-bold ml-2
                                                                    @if($game->winner_team_id === $game->team2_id) text-green-400 @endif">
                                                                    @if($game->status === 'completed')
                                                                        @if($game->tournament->maps_each_game == 0)
                                                                            {{ $game->maps->first()->team2_score ?? 0 }}
                                                                        @else
                                                                            {{ $game->team2_maps_won ?? 0 }}
                                                                        @endif
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    {{-- Eliminating teams section --}}
                                    @if(count($roundData['eliminating_scores'] ?? []) > 0)
                                        <div class="border-2 border-red-600 rounded-lg p-2 bg-red-900 bg-opacity-10">
                                            <div class="text-center mb-2">
                                                <span class="text-xs font-bold text-red-400 uppercase">Eliminated</span>
                                            </div>
                                            @foreach($roundData['eliminating_scores'] as $scoreLabel)
                                                @php
                                                    $groupData = $roundData['groups'][$scoreLabel] ?? ['games' => [], 'teams' => [], 'tbd_slots' => 0];
                                                    $teamsInGroup = $groupData['teams'] ?? [];
                                                    $tbdSlots = $groupData['tbd_slots'] ?? 0;
                                                    $hasContent = count($teamsInGroup) > 0 || $tbdSlots > 0;
                                                @endphp
                                                
                                                @if($hasContent)
                                                    <div class="mb-2">
                                                        <div class="text-center mb-1">
                                                            <div class="inline-block px-2 py-0.5 rounded-full text-xs font-bold text-white bg-red-700 border border-red-500">
                                                                {{ $scoreLabel }}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex flex-col gap-1">
                                                            @foreach($teamsInGroup as $record)
                                                                <div class="rounded p-1.5 border-2 bg-red-900 bg-opacity-30 border-red-500">
                                                                    <div class="flex items-center justify-between">
                                                                        <div class="flex items-center gap-2">
                                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-red-700 border border-red-500">
                                                                                {{ substr($record['team']->tag, 0, 2) }}
                                                                            </div>
                                                                            <span class="text-xs font-medium text-white">{{ $record['team']->tag }}</span>
                                                                        </div>
                                                                        <span class="text-xs font-bold text-red-400">✗</span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            
                                                            @for($i = 0; $i < $tbdSlots - count($teamsInGroup); $i++)
                                                                <div class="rounded p-1.5 border-2 border-dashed bg-red-900 bg-opacity-10 border-red-600">
                                                                    <div class="flex items-center justify-center">
                                                                        <span class="text-xs text-gray-500 italic">TBD</span>
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Team Modals --}}
        @foreach($tournament->teams as $team)
        <div id="team{{ $team->id }}-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $team->name }} - {{ $team->tag }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="team{{ $team->id }}-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">{{ __('manager.close') }}</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($team->players()->get() as $player)
                            @php
                            // Skip Player if not Registered for this Tournament
                            if (!$tournament->guest_mode) {
                                $teamTournament = App\Models\TeamTournament::where('tournament_id', $tournament->id)
                                ->where('team_id', $team->id)
                                ->first();
                                if (!$teamTournament->players()->where('user_id', $player->id)->exists()) {
                                    continue;
                                }
                            }
                        @endphp
                            <li class="py-3 sm:py-4">
                                <div class="flex items-center">
                                    <div class="shrink-0">
                                        <img class="w-8 h-8 rounded-full" src="{{ $player->steam_avatar }}"
                                            alt="{{ $player->steam_name }}">
                                    </div>
                                    <div class="flex-1 min-w-0 ms-4">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-blue-400">
                                            <a href="{{ $player->steam_url }}" target="_blank">{{ $player->steam_name }}</a>
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            Steam ID: {{ $player->steam_id }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="team{{ $team->id }}-modal" type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('manager.close') }}</button>

                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Matchup Modals --}}
        @foreach($tournament->games as $game)
        <div id="game{{ $game->id }}-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <div
                        class="flex items-center justify-center p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200 text-center">
                        <h3 class="text-xl text-center font-semibold text-gray-900 dark:text-white">
                            {{ $game->team1 ? $game->team1->name . ' ('.$game->team1->tag.')' : 'TBD' }} VS {{
                            $game->team2 ? $game->team2->name . ' ('.$game->team2->tag.')' : 'TBD' }}
                        </h3>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <div class="mb-4 grid grid-cols-2 auto-rows-auto gap-4">
                            <div class="col-span-2 text-center">
                                <p>Lineups</p>
                            </div>
                            <div>
                                @if($game->team1)
                                @php
                                    $team1Players = $game->team1->players()->get();
                                @endphp
                                @foreach($team1Players as $player)
                                @php
                                    // Skip Player if not Registered for this Tournament
                                    if (!$game->tournament->guest_mode) {
                                        $teamTournament = App\Models\TeamTournament::where('tournament_id', $game->tournament->id)
                                            ->where('team_id', $game->team1->id)
                                            ->first();
                                        if (!$teamTournament->players()->where('user_id', $player->id)->exists()) {
                                            continue;
                                        }
                                    }
                                @endphp
                                <div class="flex items-center mt-2">
                                    <div class="shrink-0 mr-2">
                                        <img class="w-6 h-6 rounded-full" src="{{ $player->steam_avatar }}"
                                            alt="avatar">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            <a class="text-blue-500" href="{{ $player->steam_url }}" target="_blank">{{
                                                $player->steam_name }}</a>
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                @for ($i = 0; $i < $tournament->team_size; $i++)
                                    <p>TBD</p>
                                    @endfor
                                    @endif
                            </div>
                            <div class="text-right">
                                @if($game->team2)
                                @php
                                    $team2Players = $game->team2->players()->get();
                                @endphp
                                @foreach($team2Players as $player)
                                @php
                                    // Skip Player if not Registered for this Tournament
                                    if (!$game->tournament->guest_mode) {
                                        $teamTournament = App\Models\TeamTournament::where('tournament_id', $game->tournament->id)
                                            ->where('team_id', $game->team2->id)
                                            ->first();
                                        if (!$teamTournament->players()->where('user_id', $player->id)->exists()) {
                                            continue;
                                        }
                                    }
                                @endphp
                                <div class="flex items-center mt-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            <a class="text-blue-500" href="{{ $player->steam_url }}" target="_blank">{{
                                                $player->steam_name }}</a>
                                        </p>
                                    </div>
                                    <div class="shrink-0 ml-2">
                                        <img class="w-6 h-6 rounded-full" src="{{ $player->steam_avatar }}"
                                            alt="avatar">
                                    </div>
                                </div>
                                @endforeach
                                @else
                                @for ($i = 0; $i < $tournament->team_size; $i++)
                                    <p>TBD</p>
                                    @endfor
                                    @endif
                            </div>
                            <div class="col-span-2">
                                @if($game->status === 'scheduled' && $game->team1 && $game->team2)
                                <a wire:click='startMatch({{ $game->id }})'
                                    class="block text-center cursor-pointer focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-800 focus:outline-none dark:focus:ring-green-800 w-full">{{
                                    __('manager.start_match') }}</a>

                                <form class="max-w-sm mx-auto mb-2">
                                    <label for="maps_override"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('manager.override_num_maps') }}</label>
                                    <select id="maps_override" name="maps_override" wire:change='updateMapsOverride({{ $game->id }})' wire:model='maps_override'
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option @if($game->maps_override == 0) selected @endif value="0">{{ __('manager.tournament_default') }}</option>
                                        <option @if($game->maps_override == 1) selected @endif value="1">{{ __('manager.best_of_1') }}</option>
                                        <option @if($game->maps_override == 3) selected @endif value="3">{{ __('manager.best_of_3') }}</option>
                                        <option @if($game->maps_override == 5) selected @endif value="5">{{ __('manager.best_of_5') }}</option>
                                    </select>
                                </form>

                                @elseif($game->status === 'ongoing')
                                @if($game->maps->where('status', 'ongoing')->isNotEmpty())
                                <a wire:click='pauseMatch({{ $game->id }})'
                                    class="block text-center cursor-pointer focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-yellow-600 dark:hover:bg-yellow-800 focus:outline-none dark:focus:ring-yellow-800 w-full">{{
                                    __('manager.pause_match') }}</a>
                                @elseif($game->maps->where('status', 'paused')->isNotEmpty())
                                <a wire:click='resumeMatch({{ $game->id }})'
                                    class="block text-center cursor-pointer focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-yellow-600 dark:hover:bg-yellow-800 focus:outline-none dark:focus:ring-yellow-800 w-full">{{
                                    __('manager.resume_match') }}</a>
                                @endif
                                <a wire:click='abortMatch({{ $game->id }})'
                                    class="block text-center cursor-pointer focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-800 focus:outline-none dark:focus:ring-red-800 w-full">{{
                                    __('manager.abort_match') }}</a>
                                @endif
                                <a href="{{ route('api.matches.config', $game->id) }}" target="_blank"
                                    class="block text-center cursor-pointer focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-800 focus:outline-none dark:focus:ring-gray-800 w-full">{{
                                    __('manager.show_config') }}</a>
                                <a href="{{ route('matches.show', $game->id) }}" target="_blank"
                                    class="block text-center text-white cursor-pointer focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-full">{{
                                    __('manager.view_match') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="opacity-50 cursor-not-allowed disabled"></div>
</div>

<script>
    @if($tournament->type === 0)
function drawBracketLines() {
    const container = document.getElementById('bracket-container');
    const svg = document.getElementById('bracket-lines');
    
    if (!container || !svg) return;
    
    // Clear existing lines
    svg.innerHTML = '';
    
    // Get all game elements that have a next-game-id
    const games = container.querySelectorAll('[next-game-id]:not([next-game-id=""])');
    
    games.forEach(game => {
        const nextGameId = game.getAttribute('next-game-id');
        if (!nextGameId) return;
        
        const targetGame = document.getElementById('game' + nextGameId);
        if (!targetGame) return;
        
        drawLineBetweenGames(game, targetGame, svg, container);
    });
}

function drawLineBetweenGames(sourceGame, targetGame, svg, container) {
    const containerRect = container.getBoundingClientRect();
    const sourceRect = sourceGame.getBoundingClientRect();
    const targetRect = targetGame.getBoundingClientRect();
    
    // Calculate relative positions within the container
    const sourceX = sourceRect.right - containerRect.left;
    const sourceY = sourceRect.top + sourceRect.height / 2 - containerRect.top;
    const targetX = targetRect.left - containerRect.left;
    const targetY = targetRect.top + targetRect.height / 2 - containerRect.top;
    
    // Create path for right-angled line
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    
    // Calculate the middle point for the right angle
    const midX = sourceX + (targetX - sourceX) / 2;
    
    // Create path data for right-angled line: horizontal then vertical then horizontal
    const pathData = `M ${sourceX} ${sourceY} L ${midX} ${sourceY} L ${midX} ${targetY} L ${targetX} ${targetY}`;
    
    path.setAttribute('d', pathData);
    path.setAttribute('stroke', '#ffffff');
    path.setAttribute('stroke-width', '2');
    path.setAttribute('fill', 'none');
    path.setAttribute('opacity', '0.8');
    
    svg.appendChild(path);
}

// Initialize on DOM load and setup all event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Initial draw with 100 miliseconds delay, to ensure correct rendering
    setTimeout(drawBracketLines, 100);
    
    // MutationObserver to watch for DOM changes
    const targetNode = document.getElementById('bracket-container');
    if (targetNode) {
        const observer = new MutationObserver(function(mutations) {
            let shouldRedraw = false;
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    shouldRedraw = true;
                }
            });
            if (shouldRedraw) {
                setTimeout(drawBracketLines, 100);
            }
        });
        
        observer.observe(targetNode, {
            childList: true,
            subtree: true
        });
    }
});


// Livewire 3.x events
document.addEventListener('livewire:updated', function() {
    setTimeout(drawBracketLines, 150);
});

document.addEventListener('livewire:navigated', function() {
    setTimeout(drawBracketLines, 150);
});

// Redraw lines when window is resized
window.addEventListener('resize', function() {
    setTimeout(drawBracketLines, 50);
    
});
@endif

</script>