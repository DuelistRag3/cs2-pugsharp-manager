<div class="grid grid-cols-1 md:grid-cols-2 max-w-10/12 md:max-w-6/12 mx-auto gap-2">
    <div class="grid grid-cols-1 md:grid-cols-3 md:col-span-2 px-5 py-10 bg-gray-700 gap-5 md:gap-0 rounded-xl align-middle">
        <div id="team1" class="flex justify-center items-center flex-col">
            @if(!$game->tournament->guest_mode)
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $game->team1->logoUrl() }}" alt="Team image" />
            @endif
            <a @if(!$game->tournament->guest_mode)href="{{ route('teams.show', $game->team1->id) }}" @endif
                class="text-2xl font-bold text-blue-400">{{ $game->team1 ? $game->team1->name : 'TBD' }}</a>
        </div>
        <div id="infos" class="flex justify-center items-center flex-col">
            <h2 class="text-3xl font-bold text-white">VS</h2>
            <a href="{{ route('tournaments.show', $game->tournament->id) }}"
                class="text-xs text-blue-400">{{ $game->tournament->name }}</a>
            <h4 class="font-bold text-xl text-gray-400">{{ __('manager.status_types.' . $game->status) }}</h4>
        </div>
        <div id="team2" class="flex justify-center items-center flex-col">
            @if(!$game->tournament->guest_mode)
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $game->team2->logoUrl() }}" alt="Team image" />
            @endif
            <a @if(!$game->tournament->guest_mode) href="{{ route('teams.show', $game->team2->id) }}" @endif
                class="text-2xl font-bold text-blue-400">{{ $game->team2 ? $game->team2->name : 'TBD' }}</a>
        </div>
    </div>
    <div class="grid grid-rows-[fit-content(100%)_auto]" id="maps" wire:poll>
        <h4 class="">{{ __('manager.maps') }}</h4>
        <div>
            @forelse ($game->maps->all() as $map)
                @php
                    $amap = App\Models\AvailableMaps::where('map_code', $map->map_name)->first();
                @endphp
                <div class="relative rounded w-full overflow-hidden bg-gray-700">
                    <!-- Bild -->
                    <div class="absolute inset-0 bg-center bg-cover mask-b-from-20% mask-b-to-80%"
                        style="background-image: url('{{ $amap->image_url }}');"></div>

                    <!-- Gradient -->
                    {{-- <div class="absolute inset-0 bg-gradient-to-t from-gray-800 via-gray-800 to-transparent"></div> --}}

                    <!-- Content -->
                    <div class="relative z-10 p-2 text-white">
                        <h2 class="text-2xl font-bold">{{ $amap->name }}</h2>
                        <div class="flex justify-between">
                            <span>{{ $map->team1_score }}</span>
                            <span>{{ $map->team2_score }}</span>
                        </div>
                    </div>
                </div>
            @empty
                @php
                    $numberOfMaps = $game->maps_override ? $game->maps_override : $game->tournament->maps_each_game;
                @endphp
                @for ($i = 0; $i < $numberOfMaps; $i++)
                    <div class="relative rounded w-full overflow-hidden bg-gray-700">
                    <!-- Bild -->
                    <div class="absolute inset-0 bg-center bg-cover mask-b-from-20% mask-b-to-80%"
                        style="background-image: url('{{ Vite::asset('resources/images/maps.jpg') }}')"></div>

                    <!-- Gradient -->
                    {{-- <div class="absolute inset-0 bg-gradient-to-t from-gray-800 via-gray-800 to-transparent"></div> --}}

                    <!-- Content -->
                    <div class="relative z-10 p-2 text-white">
                        <h2 class="text-2xl font-bold">TBD</h2>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>
    </div>
    <div class="grid" id="players">
        <h4 class="md:text-right">{{ __('manager.players') }}</h4>
        @if ($game->team1)
            {{ $game->team1->name }}:
            @foreach ($game->team1->players()->get() as $player)
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
                <a href="@if(!$game->tournament->guest_mode){{ route('profile.show', $player->id) }}@else {{ $player->steam_url }} @endif" class="flex items-center py-2">
                    <img class="w-8 h-8 rounded-full" src="{{ $player->profilePicture() }}"
                        alt="Player avatar" />
                    <span class="text-blue-400 ml-2">{{ $player->steam_name }}</span>
                </a>
            @endforeach
        @endif
        @if ($game->team2)
            {{ $game->team2->name }}:
            @foreach ($game->team2->players()->get() as $player)
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
                <a href="@if(!$game->tournament->guest_mode){{ route('profile.show', $player->id) }}@else {{ $player->steam_url }} @endif" class="flex items-center py-2">
                    <img class="w-8 h-8 rounded-full" src="{{ $player->profilePicture() }}"
                        alt="Player avatar" />
                    <span class="text-blue-400 ml-2">{{ $player->steam_name }}</span>
                </a>
            @endforeach
        @endif
    </div>
    <div class="col-span-1 md:col-span-2">
        @if($currentMap = $game->maps()->where('status', 'ongoing')->first())
        <h4 class="">{{ __('manager.scoreboard') }}</h4>
        @php
            
            $amap = App\Models\AvailableMaps::where('map_code', $currentMap->map_name)->first();
            $currentRound = $currentMap->team1_score + $currentMap->team2_score + 1;
            $imgurl = $amap->image_url;
            $server = $game->server;
            $t1side = RconController::sendCommand($server->id, "ps_team1_side");
            $t2side = RconController::sendCommand($server->id, "ps_team2_side");
        @endphp
        {{ $t1side }} {{ $t2side }}
        <div class="w-full bg-cover bg-center relative overflow-hidden py-2 px-4">
            <div class="z-10 relative">    
                {{-- Header --}}
                <div class="w-full grid grid-cols-3 justify-between mb-2">
                    <div class="col-span-1">
                        R: {{ $currentRound }} - {{ $amap->name }}
                    </div>
                    <div class="col-span-1 text-center">
                        <p><span>{{ $currentMap->team1_score }}</span>:<span>{{ $currentMap->team2_score }}</span></p>
                    </div>
                </div>

                {{-- Scoreboard --}}
                @if(!$game->tournament->guest_mode)
                <table class="w-full table-fixed border-separate border-spacing-y-0.5 mb-5">
                    <thead>
                        <tr class="bg-gray-800/80">
                            <th class="pl-2 text-left w-9/12"><p>{{ $game->team1->name }}</p></th>
                            <th class="w-1/12">K</th>
                            <th class="w-1/12">A</th>
                            <th class="w-1/12">D</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($game->team1->players()->get() as $player)
                        @php
                            // Skip Player if not Registered for this Tournament
                            $teamTournament = App\Models\TeamTournament::where('tournament_id', $game->tournament->id)
                                ->where('team_id', $game->team1->id)
                                ->first();
                            if (!$teamTournament->players()->where('user_id', $player->id)->exists()) {
                                continue;
                            }
                            $score = $currentMap->playerScores()->where('steam_id', $player->steam_id)->first()
                        @endphp
                        <tr class="bg-gray-800/50">
                            <td class="pl-1">{{ $player->name }}</td>
                            <td class="text-center">{{ $score->kills }}</td>
                            <td class="text-center">{{ $score->assists }}</td>
                            <td class="text-center">{{ $score->deaths }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <table class="w-full table-fixed border-separate border-spacing-y-0.5">
                    <thead>
                        <tr class="bg-gray-800/80">
                            <th class="pl-2 text-left w-9/12"><p>{{ $game->team2->name }}</p></th>
                            <th class="w-1/12">K</th>
                            <th class="w-1/12">A</th>
                            <th class="w-1/12">D</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($game->team2->players()->get() as $player)
                        @php
                            // Skip Player if not Registered for this Tournament
                            $teamTournament = App\Models\TeamTournament::where('tournament_id', $game->tournament->id)
                                ->where('team_id', $game->team2->id)
                                ->first();
                            if (!$teamTournament->players()->where('user_id', $player->id)->exists()) {
                                continue;
                            }
                            $score = $currentMap->playerScores()->where('steam_id', $player->steam_id)->first()
                        @endphp
                        <tr class="bg-gray-800/50">
                            <td class="pl-1">{{ $player->name }}</td>
                            <td class="text-center">{{ $score->kills }}</td>
                            <td class="text-center">{{ $score->assists }}</td>
                            <td class="text-center">{{ $score->deaths }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    {{ __('manager.scoreboard_disabled') }}
                @endif
            </div>

            {{-- Background Image --}}
            <div class="absolute inset-0 bg-center bg-cover opacity-25 z-0" style="background-image: url('{{ $amap->image_url }}')" ></div>
        </div>
        @endif
    </div>
</div>
