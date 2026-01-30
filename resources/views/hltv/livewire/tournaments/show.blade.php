<div>
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white">{{ __('manager.tournament') }}: {{ $tournament->name }}</h1>
            @if ($tournament->registrationAllowed())
                <button type="button" data-modal-target="register-modal" data-modal-toggle="register-modal"
                    class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 cursor-pointer">{{ __('manager.register_team') }}</button>
            @endif
            @if (!$tournament->guest_mode)
                @if ($tournament->teams()->where('captain_id', auth()->id())->exists() && $tournament->status === 'scheduled')
                    <button type="button"
                        wire:click='cancelRegistration({{ $tournament->teams()->where('captain_id', auth()->id())->first()->id }})'
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300
                font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700
                dark:focus:ring-red-800 cursor-pointer">{{ __('manager.cancel_registration') }}</button>
                @endif
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-tournament-details-card :tournament=$tournament />

            <div class="bg-gray-800 text-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">{{ __('manager.available_maps') }}</h2>
                @foreach ($tournament->availableMaps as $map)
                    <div class="flex items-center mb-2">
                        <img src="{{ $map->getImageUrlAttribute() }}" alt="{{ $map->name }}"
                            class="w-16 h-9 rounded mr-4">
                        <span class="text-lg font-medium">{{ $map->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">{{ __('manager.teams') }}:
                ({{ $tournament->teams->count() }}/{{ $tournament->max_teams }})</h2>
            @if ($tournament->teams->isEmpty())
                <p>{{ __('manager.no_teams_registered') }}</p>
            @else
                <ul class="list-disc">
                    @foreach ($tournament->teams as $team)
                        <button data-modal-target="team{{ $team->id }}-modal"
                            data-modal-toggle="team{{ $team->id }}-modal"
                            class="bg-blue-100 cursor-pointer hover:bg-blue-200 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center mt-2">{{ $team->name }}</button>
                    @endforeach
                </ul>

            @endif
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">{{ __('manager.tournament_plan') }}</h2>
            @if ($tournament->type === 0) {{-- Bracket style --}}
                @php
                    $numberOfRounds = $tournament->games->max('round') ?? 0;
                    $offset = 0;
                @endphp
                <div id="bracket-container" class="relative grid grid-rows-1 gap-4 grid-cols-{{ $numberOfRounds }}"
                    wire:poll>
                    <svg id="bracket-lines" class="absolute inset-0 w-full h-full pointer-events-none"
                        style="z-index: 1;" wire:ignore>

                    </svg>
                    @for ($round = 0; $round < $numberOfRounds; $round++)
                        <div class="mb-4">
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
                                    $roundGames = $tournament
                                        ->games()
                                        ->where('round', $round + 1)
                                        ->get();
                                    $offset = $offset + $roundGames->count();
                                @endphp

                                @foreach ($roundGames as $game)
                                    <div id="game{{ $game->id }}" next-game-id="{{ $game->next_game_id }}"
                                        class="max-w-48 text-sm font-medium mb-2 text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white relative @if ($game->status === 'ongoing') border-blue-400! dark:border-blue-700! @elseif($game->status === 'completed') border-green-400! dark:border-green-700! @elseif($game->status === 'canceled') border-red-400! dark:border-red-700! @endif"
                                        style="">

                                        <x-tournament-game-card :game=$game />

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endfor
            @endif
        </div>
    </div>

    {{-- team modals --}}
    @foreach ($tournament->teams as $team)
        <div id="team{{ $team->id }}-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                {{--
            <!-- Modal content --> --}}
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    {{--
                <!-- Modal header --> --}}
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
                    {{--
                <!-- Modal body --> --}}
                    <div class="p-4 md:p-5 space-y-4">
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($team->players()->get() as $player)
                                @php
                                    // Skip Player if not Registered for this Tournament
                                    if (!$tournament->guest_mode) {
                                        $teamTournament = App\Models\TeamTournament::where(
                                            'tournament_id',
                                            $tournament->id,
                                        )
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
                                                <a href="{{ $player->steam_url }}"
                                                    target="_blank">{{ $player->steam_name }}</a>
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
                    {{--
                <!-- Modal footer --> --}}
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="team{{ $team->id }}-modal" type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Schließen</button>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Matchup Modals --}}
    @foreach ($tournament->games as $game)
        <div id="game{{ $game->id }}-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <div
                        class="flex items-center justify-center p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200 text-center">
                        <h3 class="text-xl text-center font-semibold text-gray-900 dark:text-white">
                            {{ $game->team1 ? $game->team1->name . ' (' . $game->team1->tag . ')' : 'TBD' }} VS
                            {{ $game->team2 ? $game->team2->name . ' (' . $game->team2->tag . ')' : 'TBD' }}
                        </h3>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <div class="mb-4 grid grid-cols-2 auto-rows-auto gap-4">
                            <div class="col-span-2 text-center">
                                <p>Lineups</p>
                            </div>
                            <div>
                                @if ($game->team1)
                                    @php
                                        $team1Players = $game->team1->players()->get();
                                    @endphp
                                    @foreach ($team1Players as $player)
                                        @php
                                            // Skip Player if not Registered for this Tournament
                                            if (!$game->tournament->guest_mode) {
                                                $teamTournament = App\Models\TeamTournament::where(
                                                    'tournament_id',
                                                    $game->tournament->id,
                                                )
                                                    ->where('team_id', $game->team1->id)
                                                    ->first();
                                                if (
                                                    !$teamTournament->players()->where('user_id', $player->id)->exists()
                                                ) {
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
                                                    <a class="text-blue-500" href="{{ $player->steam_url }}"
                                                        target="_blank">{{ $player->steam_name }}</a>
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
                                @if ($game->team2)
                                    @php
                                        $team2Players = $game->team2->players()->get();
                                    @endphp
                                    @foreach ($team2Players as $player)
                                        @php
                                            // Skip Player if not Registered for this Tournament
                                            if (!$game->tournament->guest_mode) {
                                                $teamTournament = App\Models\TeamTournament::where(
                                                    'tournament_id',
                                                    $game->tournament->id,
                                                )
                                                    ->where('team_id', $game->team2->id)
                                                    ->first();
                                                if (
                                                    !$teamTournament->players()->where('user_id', $player->id)->exists()
                                                ) {
                                                    continue;
                                                }
                                            }
                                        @endphp
                                        <div class="flex items-center mt-2">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                    <a class="text-blue-500" href="{{ $player->steam_url }}"
                                                        target="_blank">{{ $player->steam_name }}</a>
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
                                <a href="{{ route('matches.show', $game->id) }}" target="_blank"
                                    class="block text-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-full">{{ __('manager.view_match') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if ($tournament->registrationAllowed())
        <!-- Register modal -->
        @if (!$tournament->guest_mode)
            <div id="register-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white"
                                id="register_team_modal_title">
                                {{ __('manager.register_team') }}
                            </h3>
                            <button type="button"
                                class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="register-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">{{ __('manager.close') }}</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5" id="register_team_modal_body">
                            @foreach ($avlTeams as $team)
                                <div wire:click='selectTeam({{ $team->id }})'
                                    class="flex items-center w-full cursor-pointer mt-2 p-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-900">

                                    <img src="{{ $team->logoUrl() }}" alt="logo" class="w-10 h-10 rounded-full">

                                    <div class="ml-4">
                                        <h2 class="text-lg font-bold dark:text-gray-300">{{ $team->name }}
                                            [{{ $team->tag }}]
                                        </h2>
                                        {{-- <h4 class=" text-gray-500 dark:text-gray-400">{{ __('manager.captain') }}: {{
                                $team->captain->name ?? 'N/A' }}</h4>
                            <h5 class="text-gray-500 dark:text-gray-400 text-sm">
                                {{ __('manager.players') }}:
                                @foreach ($team->players as $player)
                                <span class="block">{{ $player->steam_name }}</span><br>
                                @endforeach
                            </h5> --}}
                                    </div>
                                </div>
                            @endforeach
                            @if ($avlTeams->isEmpty())
                                <div class="text-gray-500 dark:text-gray-400">
                                    {{ __('manager.no_available_teams') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @elseif($tournament->guest_mode)
            <div id="register-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white"
                                id="register_team_modal_title">
                                {{ __('manager.register_team') }}
                            </h3>
                            <button type="button"
                                class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="register-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">{{ __('manager.close') }}</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5" id="register_team_modal_body">
                            <form wire:submit='registerGuestTeam' class="space-y-4" action="#">
                                <div>
                                    <label for="teamname"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('teamname') dark:text-red-500! @enderror">{{ __('manager.team_name') }}
                                        @error('teamname')
                                            ({{ $message }})
                                        @enderror
                                    </label>
                                    <input wire:model='teamname' type="text" name="teamname" id="teamname"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="{{ __('manager.team_name') }}" required />
                                </div>
                                <div>
                                    <label for="teamtag"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('teamtag') dark:text-red-500! @enderror">{{ __('manager.team_tag') }}
                                        @error('teamtag')
                                            ({{ $message }})
                                        @enderror
                                    </label>
                                    <input wire:model='teamtag' type="text" name="teamtag" id="teamtag"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="{{ __('manager.team_tag') }}" required />
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    {{ __('manager.steam_ids_help') }}</p>
                                <p id="helper-text-explanation" class="mt-2.5 text-sm text-body">Steam64 ID auf <a
                                        href="https://steamid.io/lookup" target="_blank"
                                        class="font-medium text-fg-brand hover:underline ">Steamid.io</a> bekommen.</p>
                                @for ($i = 1; $i <= $tournament->team_size; $i++)
                                    <div>
                                        <label for="steam_ids.{{ $i }}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('steam_ids.{{ $i }}') dark:text-red-500! @enderror">{{ __('manager.steam_id_player', ['number' => $i]) }}
                                            @error('steam_ids.{{ $i }}')
                                                ({{ $message }})
                                            @enderror
                                        </label>
                                        <input type="text" name="steam_ids.{{ $i }}"
                                            id="steam_ids.{{ $i }}"
                                            wire:model='steam_ids.{{ $i }}'
                                            placeholder="{{ __('manager.steam_id_player', ['number' => $i]) }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            required />
                                    </div>
                                @endfor
                            </form>

                            <button type="submit" wire:loading.attr="disabled"
                                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:bg-blue-500 disabled:cursor-not-allowed mt-5">{{ __('manager.register_team') }}
                                <i class="fas fa-spinner fa-spin" wire:loading></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
</div>
@script
    <script>
        window.onload = function() {
            const regModalTitle = document.getElementById('register_team_modal_title');
            const regModalBody = document.getElementById('register_team_modal_body');
            const maxPlayers = {{ $tournament->team_size }};

            let selectedTeam = null;
            let selectedPlayers = [];

            $wire.on('teamRegistered', () => {
                const modal = new Modal(document.getElementById('register-modal'));
                if (modal) {
                    modal.hide();
                    document.querySelector("body > div[modal-backdrop]")?.remove();
                }
            });

            $wire.on('teamSelected', (event) => {
                regModalTitle.innerHTML = "{{ __('manager.select_players') }}";
                regModalBody.innerHTML = "";
                const data = event[0];
                console.log(data);
                team = data.team;
                players = data.players;
                selectedTeam = team.id;
                players.forEach(player => {
                    if (player === null) return; // Skip null players until found a better solution
                    let playerCard = document.createElement('div');
                    playerCard.className =
                        'flex items-center w-full cursor-pointer mt-2 p-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-900';
                    playerCard.id = 'player-' + player.id;
                    playerCard.innerHTML = `
                <img src="${player.avatar}" alt="avatar" class="w-10 h-10 rounded-full">
                <div class="ml-4">
                    <h2 class="text-lg font-bold dark:text-gray-300">${player.name}</h2>
                    <h4 class="text-gray-500 dark:text-gray-400">${player.steam_id}</h4>
                </div>
            `;
                    playerCard.addEventListener('click', () => {
                        addRemoveSelection(player.id);
                    });
                    regModalBody.appendChild(playerCard);
                });
                let buttonWrapper = document.createElement('div');
                buttonWrapper.className = 'flex justify-between mt-4 gap-2';
                let completeBtn = document.createElement('button');
                completeBtn.className =
                    'text-white mt-2 w-1/2 cursor-pointer bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 disabled:cursor-not-allowed disabled:bg-blue-700 disabled:hover:bg-blue-700';
                completeBtn.innerHTML = "{{ __('manager.register_team') }}";
                completeBtn.addEventListener('click', () => {
                    $wire.registerTeam(selectedTeam, selectedPlayers);
                });
                completeBtn.id = 'complete-btn';
                let cancelBtn = document.createElement('button');
                cancelBtn.className =
                    'text-white mt-2 w-1/2 cursor-pointer bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 ';
                cancelBtn.innerHTML = "{{ __('manager.cancel') }}";
                completeBtn.setAttribute('disabled', true);
                // cancelBtn.addEventListener('click', () => {

                // });
                cancelBtn.setAttribute('wire:click', "$refresh");
                buttonWrapper.appendChild(cancelBtn);
                buttonWrapper.appendChild(completeBtn);
                regModalBody.appendChild(buttonWrapper);
            });

            function addRemoveSelection(playerId) {
                const playerCard = document.getElementById('player-' + playerId);
                if (selectedPlayers.length === maxPlayers) {
                    document.getElementById('complete-btn').setAttribute('disabled', true);
                } else {
                    document.getElementById('complete-btn').removeAttribute('disabled');
                }
                if (selectedPlayers.length >= maxPlayers && !selectedPlayers.includes(playerId)) {
                    $wire.playerSelectionLimitReached(maxPlayers);
                    return;
                }
                if (playerCard) {
                    playerCard.classList.toggle('border-green-500!');
                }

                if (selectedPlayers.includes(playerId)) {
                    selectedPlayers = selectedPlayers.filter(id => id !== playerId);
                } else {
                    selectedPlayers.push(playerId);
                }
                console.log('Selected Players:', selectedPlayers);
            }

            $wire.on('teamRegistered', () => {
                // Remove Modal backdrop
                const modal = new Modal(document.getElementById('register-modal'));
                if (modal) {
                    modal.hide();
                    document.querySelector("body > div[modal-backdrop]")?.remove();
                }
            });
        }
    </script>
@endscript
<script>
    @if ($tournament->type === 0)
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
            const pathData =
            `M ${sourceX} ${sourceY} L ${midX} ${sourceY} L ${midX} ${targetY} L ${targetX} ${targetY}`;

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
