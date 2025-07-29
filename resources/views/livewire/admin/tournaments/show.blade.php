<div
    class="grid-cols-1 grid-cols-2 grid-cols-3 grid-cols-4 grid-cols-5 grid-cols-6 grid-cols-7 grid-cols-8 grid-cols-9 grid-cols-10">
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white">{{ __('manager.tournament') }}: {{ $tournament->name }}</h1>
            <div class="right">
                @if($tournament->status === 'ongoing')
                <button wire:click='cancelTournament()' type="button"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cursor-pointer">{{ __('manager.cancel_tournament') }}</button>
                @if($tournament->games->isEmpty())
                <button wire:click='generateMatchPlan(0)' type="button"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer">{{ __('manager.generate_bracket_matchplan') }}</button>
                <button wire:click='generateMatchPlan(1)' type="button"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer">{{ __('manager.generate_round_robin_matchplan') }}</button>
                @else
                <button wire:click='resetMatchPlan()' type="button"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cursor-pointer">{{ __('manager.reset_matchplan') }}</button>
                <button wire:click='addTeamsToMatchPlan()' type="button"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer">
                    {{ __('manager.scramble_teams') }}
                </button>
                <button wire:click='removeAllTeamsFromMatchPlan()' type="button"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cursor-pointer">{{ __('manager.empty_matchplan') }}</button>
                @endif
                @endif
                @if($tournament->status === 'scheduled')
                <button type="button" data-modal-target="create-modal" data-modal-toggle="create-modal"
                    class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 cursor-pointer"><i
                        class="fa-solid fa-plus"></i> {{ __('manager.edit') }}</button>
                <button wire:click='startTournament()' type="button"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer">{{ __('manager.start_tournament') }}</button>
                @endif
            </div>
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">{{ __('manager.details') }}</h2>
            <p><strong>{{ __('manager.registration_deadline') }}:</strong>
                @if ($tournament->registration_deadline)
                {{ new DateTime($tournament->registration_deadline)->format('d.m.Y H:i') }}
                @else
                {{ new Datetime($tournament->start_date)->format('d.m.Y H:i') }}
                @endif
            </p>
            <p><strong>{{ __('manager.start_date') }}:</strong> {{ new DateTime($tournament->start_date)->format('d.m.Y H:i') }}</p>
            <p><strong>{{ __('manager.end_date') }}:</strong> @if($tournament->status != 'completed' || $tournament->status != 'cancelled')
                {{ __('manager.tournament_not_finished') }} @else {{ new DateTime($tournament->end_date)->format('d.m.Y H:i') }} @endif
            </p>
            <p><strong>{{ __('manager.max_teams') }}:</strong> {{ $tournament->max_teams }}</p>
            <p><strong>{{ __('manager.team_size') }}:</strong> {{ $tournament->team_size }}</p>
            <p><strong>{{ __('manager.gametype') }}:</strong>
                @switch($tournament->matchup_rounds)
                @case(0)
                <span class="text-blue-500">Best Of 1</span>
                @break
                @case(1)
                <span class="text-blue-500">Best Of 3</span>
                @break
                @case(2)
                <span class="text-blue-500">Best Of 5</span>
                @break
                @endswitch
            </p>
            <p><strong>{{ __('manager.gametype') }} ({{ __('manager.final') }}):</strong>
                @switch($tournament->final_rounds)
                @case(0)
                <span class="text-blue-500">Best Of 1</span>
                @break
                @case(1)
                <span class="text-blue-500">Best Of 3</span>
                @break
                @case(2)
                <span class="text-blue-500">Best Of 5</span>
                @break
                @endswitch
            </p>
            <p><strong>{{ __('manager.status') }}:</strong>
                @switch($tournament->status)
                @case('scheduled')
                <span class="text-yellow-500">{{ __('manager.status_types.scheduled') }}</span>
                @break
                @case('ongoing')
                <span class="text-green-500">{{ __('manager.status_types.ongoing') }}</span>
                @break
                @case('completed')
                <span class="text-gray-500">{{ __('manager.status_types.completed') }}</span>
                @break
                @case('cancelled')
                <span class="text-red-500">{{ __('manager.status_types.cancelled') }}</span>
                @default
                <span class="text-red-500">{{ __('manager.status_types.unknown') }}</span>
                @endswitch
            </p>
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">{{ __('manager.maps') }}</h2>
            <div class="grid grid-cols-12 gap-4">
                @foreach($availableMaps as $map)
                    <div wire:click='changeMapState({{ $map->id }})' wire:loading.class="opacity-50 cursor-not-allowed disabled"
                        class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 col-span-2 cursor-pointer @if($selectedMaps) @if(in_array($map->map_code, $selectedMaps)) bg-green-100 dark:bg-green-900 @endif @endif">
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
                    class="bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center mt-2">{{
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
            <div id="bracket-container" class="relative grid grid-rows-1 gap-4 grid-cols-{{ ($numberOfRounds) }}" wire:poll>
                <svg id="bracket-lines" class="absolute inset-0 w-full h-full pointer-events-none" style="z-index: 1;" wire:ignore>
                    
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
                            class="max-w-48 text-sm font-medium mb-2 text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white relative @if($game->status === 'ongoing') border-blue-400! dark:border-blue-700! @elseif($game->status === 'completed') border-green-400! dark:border-green-700! @elseif($game->status === 'canceled') border-red-400! dark:border-red-700! @endif"
                            style="">
                            <button type="button" data-dropdown-toggle="gamemenu{{ $game->id }}" data-dropdown-placement="right"
                                class="cursor-pointer text-xs text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded p-2 text-center inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 shadow-lg">
                                <i class="fa-solid fa-wrench"></i>
                                <span class="sr-only">{{ __('manager.menu') }}</span>
                            </button>
                            <div id="gamemenu{{ $game->id }}" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-600">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="gamemenu{{ $game->id }}">
                                    @if($game->status === 'scheduled' && $game->team1 && $game->team2)
                                        <li>
                                            <a wire:click="startMatch({{ $game->id }})" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white cursor-pointer">Match starten</a>
                                        </li>
                                    @elseif($game->status === 'ongoing')
                                        @if($game->maps->where('status', 'ongoing')->isNotEmpty())
                                            <li>
                                                <a wire:click="pauseMatch({{ $game->id }})" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white cursor-pointer">Match pausieren</a>
                                            </li>
                                        @elseif($game->maps->where('status', 'paused')->isNotEmpty())
                                            <li>
                                                <a wire:click="resumeMatch({{ $game->id }})" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white cursor-pointer">Match fortsetzen</a>
                                            </li>
                                        @endif
                                        <li>
                                            <a wire:click="abortMatch({{ $game->id }})" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-red-500 dark:hover:text-white cursor-pointer">Match abbrechen</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ route('api.matches.config', $game->id) }}" target="_blank" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">Zeige Config</a>
                                    </li>
                                </ul>
                            </div>
                            @if($game->team1)
                            <a aria-current="true" data-modal-target="team{{ $game->team1->id }}-modal"
                                data-modal-toggle="team{{ $game->team1->id }}-modal"
                                data-team-id="{{ $game->team1->id }}"
                                class="block w-full px-4 py-2 border-b rounded-t-lg border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white transition-all duration-200">
                                {{ $game->team1->name }}
                                <span class="float-end @if($game->winner_team_id == $game->team1->id) font-bold text-green-500 @endif">
                                    @if($game->tournament->maps_each_game == 0)
                                    @if($game->maps->isNotEmpty())
                                    {{ $game->maps->first()->team1_score }}
                                    @else
                                    0
                                    @endif
                                    @else
                                    {{ $game->team1_maps_won }}
                                    @endif
                                </span>
                            </a>
                            @else
                            <a aria-current="true"
                                class="block w-full px-4 py-2 border-b rounded-t-lg border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                                TBD
                            </a>
                            @endif
                            @if($game->team2)
                            <a data-modal-target="team{{ $game->team2->id }}-modal"
                                data-modal-toggle="team{{ $game->team2->id }}-modal"
                                data-team-id="{{ $game->team2->id }}"
                                class="block w-full px-4 py-2 rounded-b-lg cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white transition-all duration-200">
                                {{ $game->team2->name }}
                                <span class="float-end @if($game->winner_team_id == $game->team2->id) font-bold text-green-500 @endif">
                                    @if($game->tournament->maps_each_game == 0)
                                    @if($game->maps->isNotEmpty())
                                    {{ $game->maps->first()->team2_score }}
                                    @else
                                    0
                                    @endif
                                    @else
                                    {{ $game->team2_maps_won }}
                                    @endif
                                </span>
                            </a>
                            @else
                            <a
                                class="block w-full px-4 py-2 rounded-b-lg cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                                TBD
                            </a>
                            @endif

                        </div>
                        @endforeach
                    </div>
            </div>
            @endfor
            @elseif ($tournament->type === 1)
            <div class="grid grid-cols-1 gap-4">
                @foreach($tournament->games as $game)
                <div
                    class="max-w-48 text-sm font-medium mb-2 text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white relative">
                    <button
                        class="cursor-pointer text-xs text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full p-2 text-center inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 shadow-lg">
                        <i class="fa-solid fa-wrench"></i>
                        <span class="sr-only">Menü</span>
                    </button>
                    @if($game->team1)
                    <a aria-current="true" data-modal-target="team{{ $game->team1->id }}-modal"
                        data-modal-toggle="team{{ $game->team1->id }}-modal"
                        class="block w-full px-4 py-2 border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                        {{ $game->team1->name }}
                    </a>
                    @else
                    <a aria-current="true"
                        class="block w-full px-4 py-2 border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                        TBD
                    </a>
                    @endif
                    @if($game->team2)
                    <a data-modal-target="team{{ $game->team1->id }}-modal"
                        data-modal-toggle="team{{ $game->team1->id }}-modal"
                        class="block w-full px-4 py-2 rounded-b-lg cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                        {{ $game->team2->name }}
                    </a>
                    @else
                    <a
                        class="block w-full px-4 py-2 rounded-b-lg cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                        TBD
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

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
                            <span class="sr-only">Schließen</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($team->players as $player)
                            <li class="py-3 sm:py-4">
                                <div class="flex items-center">
                                    <div class="shrink-0">
                                        <img class="w-8 h-8 rounded-full" src="{{ $player->steam_avatar }}"
                                            alt="{{ $player->name }}">
                                    </div>
                                    <div class="flex-1 min-w-0 ms-4">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-blue-400">
                                            <a href="{{ $player->steam_url }}" target="_blank">{{ $player->steam_name
                                                }}</a>
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
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Schließen</button>

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