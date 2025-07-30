<a data-modal-target="game{{ $game->id }}-modal" data-modal-toggle="game{{ $game->id }}-modal"
    data-team-id="{{ $game->team1 ? $game->team1->id : 'null' }}"
    class="flex w-full px-4 py-2 border-b rounded-t-lg border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
    {{ $game->team1 ? $game->team1->name : 'TBD' }}
    <span
        class="mr-0 ml-auto {{ $game->team1 && ($game->tournament->maps_each_game == 0 ? ($game->maps->isNotEmpty() ? $game->maps->first()->team1_score : 0) : ($game->team1_maps_won ? $game->team1_maps_won : 0)) ? 'text-blue-500' : 'text-gray-500' }}">
        {{ ($game->team1 ? ($game->tournament->maps_each_game == 0 ? ($game->maps->isNotEmpty() ?
        $game->maps->first()->team1_score : 0) : ($game->team1_maps_won ? $game->team1_maps_won : 0)) : 0) }}
    </span>
</a>
<a data-modal-target="game{{ $game->id }}-modal" data-modal-toggle="game{{ $game->id }}-modal"
    data-team-id="{{ $game->team2 ? $game->team2->id : 'null' }}"
    class="flex w-full px-4 py-2 rounded-b-lg cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
    {{ $game->team2 ? $game->team2->name : 'TBD' }}
    <span
        class="mr-0 ml-auto {{ $game->team2 && ($game->tournament->maps_each_game == 0 ? ($game->maps->isNotEmpty() ? $game->maps->first()->team2_score : 0) : ($game->team2_maps_won ? $game->team2_maps_won : 0)) ? 'text-blue-500' : 'text-gray-500' }}">
        {{ ($game->team2 ? ($game->tournament->maps_each_game == 0 ? ($game->maps->isNotEmpty() ?
        $game->maps->first()->team2_score : 0) : ($game->team2_maps_won ? $game->team2_maps_won : 0)) : 0) }}
    </span>
</a>