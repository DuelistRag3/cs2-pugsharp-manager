<div class="grid grid-cols-1 md:grid-cols-2 max-w-10/12 md:max-w-6/12 mx-auto gap-2">
    <div class="grid grid-cols-1 md:grid-cols-3 md:col-span-2 px-5 py-10 bg-gray-700 gap-5 md:gap-0 rounded-xl align-middle">
        <div id="team1" class="flex justify-center items-center flex-col">
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $game->team1->logoUrl() }}" alt="Team image" />
            <a href="{{ route('teams.show', $game->team1->id) }}"
                class="text-2xl font-bold text-blue-400">{{ $game->team1 ? $game->team1->name : 'TBD' }}</a>
        </div>
        <div id="infos" class="flex justify-center items-center flex-col">
            <h2 class="text-3xl font-bold text-white">VS</h2>
            <a href="{{ route('tournaments.show', $game->tournament->id) }}"
                class="text-xs text-blue-400">{{ $game->tournament->name }}</a>
            <h4 class="font-bold text-xl text-gray-400">{{ __('manager.status_types.' . $game->status) }}</h4>
        </div>
        <div id="team2" class="flex justify-center items-center flex-col">
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $game->team2->logoUrl() }}" alt="Team image" />
            <a href="{{ route('teams.show', $game->team2->id) }}"
                class="text-2xl font-bold text-blue-400">{{ $game->team2 ? $game->team2->name : 'TBD' }}</a>
        </div>
    </div>
    <div class="grid grid-rows-[fit-content(100%)_auto]" id="maps" wire:poll>
        <h4 class="">{{ __('manager.maps') }}</h4>
        <div>
            @foreach ($game->maps as $map)
                @php
                    $amap = App\Models\AvailableMaps::where('map_code', $map->map_name)->first();
                @endphp
                <div class="relative rounded w-full overflow-hidden">
                    <!-- Bild -->
                    <div class="absolute inset-0 bg-center bg-cover"
                        style="background-image: url('{{ $amap->image_url }}');"></div>

                    <!-- Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-800 via-gray-800 to-transparent"></div>

                    <!-- Content -->
                    <div class="relative z-10 p-2 text-white">
                        <h2 class="text-2xl font-bold">{{ $amap->name }}</h2>
                        <div class="flex justify-between">
                            <span>{{ $map->team1_score }}</span>
                            <span>{{ $map->team2_score }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="grid" id="players">
        <h4 class="md:text-right">{{ __('manager.players') }}</h4>
        @if ($game->team1)
            {{ $game->team1->name }}:
            @foreach (App\Models\TeamTournament::where('tournament_id', $game->tournament->id)->where('team_id', $game->team1->id)->first()->players()->get() as $player)
                <a href="{{ route('profile.show', $player->user->id) }}" class="flex items-center py-2">
                    <img class="w-8 h-8 rounded-full" src="{{ $player->user->profilePicture() }}"
                        alt="Player avatar" />
                    <span class="text-blue-400 ml-2">{{ $player->user->name }}</span>
                </a>
            @endforeach
        @endif
        @if ($game->team2)
            {{ $game->team2->name }}:
            @foreach (App\Models\TeamTournament::where('tournament_id', $game->tournament->id)->where('team_id', $game->team2->id)->first()->players()->get() as $player)
                <a href="{{ route('profile.show', $player->user->id) }}" class="flex items-center py-2">
                    <img class="w-8 h-8 rounded-full" src="{{ $player->user->profilePicture() }}"
                        alt="Player avatar" />
                    <span class="text-blue-400 ml-2">{{ $player->user->name }}</span>
                </a>
            @endforeach
        @endif
    </div>
</div>
