<a href="{{ route('tournaments.show', $tournament) }}"
    class="grid grid-cols-3 w-full mt-2 p-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <h2 class="dark:text-white col-span-3">
        {{ $tournament->name }} 
        <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2 py-0.1 rounded-sm dark:bg-gray-900 dark:text-gray-300">
            {{ __('manager.best_of_' . $tournament->maps_each_game) }}
        </span>
    </h2>
    <p class="dark:text-white col-span-2">
        {{ __('manager.start_date') }}: {{ new DateTime($tournament->start_date)->format(__('manager.timeformat')) }}
    </p>
    <p class="dark:text-white col-span-2">
        {{ __('manager.teams') }}: {{ $tournament->teams()->count() }} / {{ $tournament->max_teams }}
    </p>
    <p class="dark:text-white text-end">
        <span class="text-{{ config('manager.status_colors.' . $tournament->status) }}-500 font-semibold">
            {{ __('manager.status_types.' . $tournament->status) }}
        </span>
    </p>
</a>