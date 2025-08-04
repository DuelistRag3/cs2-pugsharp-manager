<div class="grid grid-cols-1 xl:grid-cols-8 gap-2 mb-4 max-w-10/12 md:max-w-6/12 mx-auto">
    <div class="xl:col-span-3">
        <div
            class="w-full max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-end px-4 pt-4">
            </div>
            <div class="flex flex-col items-center pb-5">
                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $team->logoUrl() }}" alt="Bonnie image" />
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $team->name }}</h5>

            </div>
        </div>
        <div
            class="block mt-2 py-1 px-3 max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('manager.players') }}
            </h5>
            @foreach($team->players as $player)
                <a href="{{ route('profile.show', $player->id) }}" target="_blank" class="flex items-center py-1 {{ $loop->last ? '' : 'border-b' }} border-gray-200 dark:border-gray-700">
                    <img class="w-8 h-8 rounded-full mr-2" src="{{ $player->profilePicture() }}" alt="{{ $player->name }}" />
                    <span class="text-gray-900 dark:text-white">{{ $player->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>