<div class="grid grid-cols-2 max-w-10/12 md:max-w-6/12 mx-auto gap-2">
    <div class="grid grid-cols-3 col-span-2 px-5 py-10 bg-gray-700 rounded-xl align-middle">
        <div id="team1" class="flex justify-center items-center flex-col">
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $game->team1->logoUrl() }}" alt="Team image" />
            <a href="{{ route('teams.show', $game->team1->id) }}" class="text-2xl font-bold text-blue-400">{{ $game->team1 ? $game->team1->name : 'TBD' }}</a>
        </div>
        <div id="infos" class="flex justify-center items-center flex-col">
            <h2 class="text-3xl font-bold text-white">VS</h2>
            <a href="{{ route('tournaments.show', $game->tournament->id) }}" class="text-xs text-blue-400">{{ $game->tournament->name }}</a>
            <h4 class="font-bold text-xl text-gray-400">{{ __('manager.status_types.' . $game->status) }}</h4>
        </div>
        <div id="team2" class="flex justify-center items-center flex-col">
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $game->team2->logoUrl() }}" alt="Team image" />
            <a href="{{ route('teams.show', $game->team2->id) }}" class="text-2xl font-bold text-blue-400">{{ $game->team2 ? $game->team2->name : 'TBD' }}</a>
        </div>
    </div>
    <div class="grid" id="maps">
        <h4>{{ __('manager.maps') }}</h4>
    </div>
    <div class="grid" id="players">
        <h4 class="text-right">{{ __('manager.players') }}</h4>
        @if($game->team1)
            {{ $game->team1->name }}:
            @foreach(App\Models\TeamTournament::where('tournament_id', $game->tournament->id)
                                        ->where('team_id', $game->team1->id)
                                        ->first()->players()->get() as $player)
                <a href="{{ route('profile.show', $player->user->id) }}" class="flex items-center py-2">
                    <img class="w-8 h-8 rounded-full" src="{{ $player->user->profilePicture() }}" alt="Player avatar" />
                    <span class="text-blue-400 ml-2">{{ $player->user->name }}</span>
                </a>
            @endforeach
        @endif
        @if($game->team2)
            {{ $game->team2->name }}:
            @foreach(App\Models\TeamTournament::where('tournament_id', $game->tournament->id)
                                        ->where('team_id', $game->team2->id)
                                        ->first()->players()->get() as $player)
                <a href="{{ route('profile.show', $player->user->id) }}" class="flex items-center py-2">
                    <img class="w-8 h-8 rounded-full" src="{{ $player->user->profilePicture() }}" alt="Player avatar" />
                    <span class="text-blue-400 ml-2">{{ $player->user->name }}</span>
                </a>
            @endforeach
        @endif
    </div>
</div>
