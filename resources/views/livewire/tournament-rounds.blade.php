<div class="overflow-x-auto p-4">
    <div class="flex space-x-8">
        @foreach($bracket as $roundName => $matches)
            <div class="flex flex-col space-y-4">
                <h3 class="text-center font-semibold text-gray-900 dark:text-white">{{ $roundName }}</h3>
                @foreach($matches as [$team1, $team2])
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-3 w-48">
                        <p class="text-center text-gray-700 dark:text-gray-200">{{ $team1 }}</p>
                        <p class="text-center text-sm text-gray-500 dark:text-gray-400">vs</p>
                        <p class="text-center text-gray-700 dark:text-gray-200">{{ $team2 }}</p>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
