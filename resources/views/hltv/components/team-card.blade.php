<a href="{{ route('teams.show', $team->id) }}" 
       wire:click="$emit('teamSelected', {{ $team->id }})" 
       wire:key="team-card-{{ $team->id }}"
    class="flex items-center w-full cursor-pointer mt-2 p-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

    <img src="{{ $team->logoUrl() }}" alt="logo" class="w-10 h-10 rounded-full">

    <div class="ml-4">
        <h2 class="text-lg font-bold dark:text-gray-300">{{ $team->name }}</h2>
        <h4 class="text-sm text-gray-500 dark:text-gray-400">{{ __('manager.captain') }}: {{ $team->captain->name ?? 'N/A' }}</h4>
    </div>
</a>