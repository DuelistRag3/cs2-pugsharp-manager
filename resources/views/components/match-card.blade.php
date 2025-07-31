@php
$rounds = $match->nextGame ? $match->tournament->maps_each_game : $match->tournament->maps_final_game;
@endphp
<a href="#"
    class="grid grid-cols-3 w-full mt-2 p-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <h2 class="text-xs dark:text-gray-500 col-span-3">
        {{ $match->tournament->name }} 
        <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2 py-0.1 rounded-sm dark:bg-gray-900 dark:text-gray-300">
            @switch($rounds)
                @case(0)
                    BO1
                    @break
                @case(1)
                    BO3
                    @break
                @case(2)
                    BO5
                    @break
                @default
                    BO1
            @endswitch
        </span>
    </h2>
    <div class="col-span-3">
        <p class="flex w-full justify-between">
            <span class="pl-2">{{ $match->team1 ? $match->team1->name : 'TBD' }}</span>
            <span class="mr-5">
                @if($match->team1)
                <span class="mr-0 ml-auto text-gray-500">
                    @if($rounds == 0)
                    @if($match->maps->isNotEmpty())
                    @php
                    $map = $match->maps->first();
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
                    @if($match->status == 'ongoing')
                    @php
                    $map = $match->maps->where('status', 'ongoing')->first();
                    @endphp
                    <span class="
                                {{ $map ? $map->team1_score > $map->team2_score ? 'text-green-500!' : '' : '' }}
                                {{ $map ? $map->team1_score < $map->team2_score ? 'text-red-500!' : '' : '' }}
                                {{ $map ? $map->team1_score == $map->team2_score ? 'text-gray-500!' : '' : '' }}
                                ">
                        {{ $map ? $map->team1_score : 0 }}
                    </span> <span class="text-xs
                                        {{ $match->team1_maps_won > $match->team2_maps_won ? 'text-green-500!' : '' }}
                                        {{ $match->team1_maps_won < $match->team2_maps_won ? 'text-red-500!' : '' }}
                                        {{ $match->team1_maps_won == $match->team2_maps_won ? 'text-gray-500!' : '' }}
                                        ">({{ $match->team1_maps_won ? $match->team1_maps_won : 0 }})</span>
                    @else
                    <span class="
                            {{ $match->team1_maps_won > $match->team2_maps_won ? 'text-green-500!' : '' }}
                            {{ $match->team1_maps_won < $match->team2_maps_won ? 'text-red-500!' : '' }}
                            {{ $match->team1_maps_won == $match->team2_maps_won ? 'text-gray-500!' : '' }}
                            ">{{ $match->team1_maps_won ? $match->team1_maps_won : 0 }}</span>
                    @endif
                    @endif
                </span>
                @else
                <span class="mr-0 ml-auto text-gray-500">0</span>
                @endif
            </span>
        </p>
        <p class="flex w-full justify-between">
            <span class="pl-2">{{ $match->team2 ? $match->team2->name : 'TBD' }}</span>
            <span class="mr-5">
                @if($match->team2)
                <span class="mr-0 ml-auto text-gray-500">
                    @if($rounds == 0)
                    @if($match->maps->isNotEmpty())
                    @php
                    $map = $match->maps->first();
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
                    @if($match->status == 'ongoing')
                    @php
                    $map = $match->maps->where('status', 'ongoing')->first();
                    @endphp
                    <span class="
                                {{ $map ? $map->team2_score > $map->team1_score ? 'text-green-500!' : '' : '' }}
                                {{ $map ? $map->team2_score < $map->team1_score ? 'text-red-500!' : '' : '' }}
                                {{ $map ? $map->team2_score == $map->team1_score ? 'text-gray-500!' : '' : '' }}
                                ">
                        {{ $map ? $map->team2_score : 0 }}
                    </span> <span class="text-xs
                                        {{ $match->team2_maps_won > $match->team2_maps_won ? 'text-green-500!' : '' }}
                                        {{ $match->team2_maps_won < $match->team1_maps_won ? 'text-red-500!' : '' }}
                                        {{ $match->team2_maps_won == $match->team1_maps_won ? 'text-gray-500!' : '' }}
                                        ">({{ $match->team2_maps_won ? $match->team2_maps_won : 0 }})</span>
                    @else
                    <span class="
                            {{ $match->team2_maps_won > $match->team1_maps_won ? 'text-green-500!' : '' }}
                            {{ $match->team2_maps_won < $match->team1_maps_won ? 'text-red-500!' : '' }}
                            {{ $match->team2_maps_won == $match->team1_maps_won ? 'text-gray-500!' : '' }}
                            ">{{ $match->team2_maps_won ? $match->team2_maps_won : 0 }}</span>
                    @endif
                    @endif
                </span>
                @else
                <span class="mr-0 ml-auto text-gray-500">0</span>
                @endif
            </span>
        </p>
    </div>
</a>