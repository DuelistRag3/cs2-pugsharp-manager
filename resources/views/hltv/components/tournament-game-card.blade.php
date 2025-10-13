@php
    $rounds = $game->nextGame ? $game->tournament->maps_each_game : $game->tournament->maps_final_game;
@endphp
<a data-modal-target="game{{ $game->id }}-modal" data-modal-toggle="game{{ $game->id }}-modal"
    data-team-id="{{ $game->team1 ? $game->team1->id : 'null' }}"
    class="flex w-full px-4 py-2 border-b rounded-t-lg border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
    {{ $game->team1 ? $game->team1->name : 'TBD' }}
    @if($game->team1)
        <span class="mr-0 ml-auto text-gray-500">
            @if($rounds == 0)
                @if($game->maps->isNotEmpty())
                    @php
                        $map = $game->maps->first();
                    @endphp
                    <span class="
                                {{ $map->team1_score > $map->team2_score ? 'text-green-500!' : '' }}
                                {{ $map->team1_score < $map->team2_score ? 'text-red-500!' : '' }}
                                {{ $map->team1_score == $map->team2_score ? 'text-gray-500!' : '' }}
                                ">
                        {{ $map->team1_score }}
                    </span>
                @else
                    0
                @endif
            @else
                @if($game->status == 'ongoing')
                    @php
                        $map = $game->maps->where('status', 'ongoing')->first();
                    @endphp
                    <span class="
                                {{ $map ? $map->team1_score > $map->team2_score ? 'text-green-500!' : '' : '' }}
                                {{ $map ? $map->team1_score < $map->team2_score ? 'text-red-500!' : '' : '' }}
                                {{ $map ? $map->team1_score == $map->team2_score ? 'text-gray-500!' : '' : '' }}
                                ">
                        {{ $map ? $map->team1_score : 0 }}
                    </span> <span class="text-xs
                                        {{ $game->team1_maps_won > $game->team2_maps_won ? 'text-green-500!' : '' }}
                                        {{ $game->team1_maps_won < $game->team2_maps_won ? 'text-red-500!' : '' }}
                                        {{ $game->team1_maps_won == $game->team2_maps_won ? 'text-gray-500!' : '' }}
                                        ">({{ $game->team1_maps_won ? $game->team1_maps_won : 0 }})</span>
                @else
                <span class="
                            {{ $game->team1_maps_won > $game->team2_maps_won ? 'text-green-500!' : '' }}
                            {{ $game->team1_maps_won < $game->team2_maps_won ? 'text-red-500!' : '' }}
                            {{ $game->team1_maps_won == $game->team2_maps_won ? 'text-gray-500!' : '' }}
                            ">{{ $game->team1_maps_won ? $game->team1_maps_won : 0 }}</span>
                @endif
            @endif
        </span>
    @else
        <span class="mr-0 ml-auto text-gray-500">0</span>
    @endif
</a>
<a data-modal-target="game{{ $game->id }}-modal" data-modal-toggle="game{{ $game->id }}-modal"
    data-team-id="{{ $game->team2 ? $game->team2->id : 'null' }}"
    class="flex w-full px-4 py-2 rounded-b-lg cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
    {{ $game->team2 ? $game->team2->name : 'TBD' }}
    @if($game->team2)
        <span class="mr-0 ml-auto text-gray-500">
            @if($rounds == 0)
                @if($game->maps->isNotEmpty())
                    @php
                        $map = $game->maps->first();
                    @endphp
                    <span class="
                                {{ $map->team2_score > $map->team1_score ? 'text-green-500!' : '' }}
                                {{ $map->team2_score < $map->team1_score ? 'text-red-500!' : '' }}
                                {{ $map->team2_score == $map->team1_score ? 'text-gray-500!' : '' }}
                                ">
                        {{ $map->team2_score }}
                    </span>
                @else
                    0
                @endif
            @else
                @if($game->status == 'ongoing')
                    @php
                        $map = $game->maps->where('status', 'ongoing')->first();
                    @endphp
                    <span class="
                                {{ $map ? $map->team2_score > $map->team1_score ? 'text-green-500!' : '' : '' }}
                                {{ $map ? $map->team2_score < $map->team1_score ? 'text-red-500!' : '' : '' }}
                                {{ $map ? $map->team2_score == $map->team1_score ? 'text-gray-500!' : '' : '' }}
                                ">
                        {{ $map ? $map->team2_score : 0 }}
                    </span> <span class="text-xs
                                        {{ $game->team2_maps_won > $game->team1_maps_won ? 'text-green-500!' : '' }}
                                        {{ $game->team2_maps_won < $game->team1_maps_won ? 'text-red-500!' : '' }}
                                        {{ $game->team2_maps_won == $game->team1_maps_won ? 'text-gray-500!' : '' }}
                                        ">({{ $game->team2_maps_won ? $game->team2_maps_won : 0 }})</span>
                @else
                <span class="
                            {{ $game->team2_maps_won > $game->team1_maps_won ? 'text-green-500!' : '' }}
                            {{ $game->team2_maps_won < $game->team1_maps_won ? 'text-red-500!' : '' }}
                            {{ $game->team2_maps_won == $game->team1_maps_won ? 'text-gray-500!' : '' }}
                            ">{{ $game->team2_maps_won ? $game->team2_maps_won : 0 }}</span>
                @endif
            @endif
        </span>
    @else
        <span class="mr-0 ml-auto text-gray-500">0</span>
    @endif
</a>