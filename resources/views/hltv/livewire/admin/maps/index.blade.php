<div>
    <button type="button" data-modal-target="add-modal" data-modal-toggle="add-modal"
        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer col-span-2"><i
            class="fa-solid fa-plus"></i> {{ __('manager.add') }}</button>
    @if ($maps->count() == 0)
        <button type="button" wire:click='addDefaultMaps' wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
            class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 cursor-pointer col-span-2"><i
                class="fa-solid fa-plus"></i> 
                {{ __('manager.add_active_mappool') }}
                <i class="fa-solid fa-spinner fa-spin" wire:loading.delay.long></i>
            </button>
    @endif
    @if ($maps->count() > 0)
        <button type="button" wire:click='confirmDeleteAll' wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed!" id="deleteAllButton" wire:target="deleteAll"
            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 cursor-pointer col-span-2"><i
                class="fa-solid fa-trash" wire:target="deleteAll" wire:loading.class="fa-spinner fa-spin" wire:loading.class.remove="fa-trash"></i> {{ __('manager.remove_all_maps') }}
        </button>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
        @if (!$maps->count())
            <div class="col-span-12">
                <div
                    class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-5">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('manager.no_maps_found') }}</h5>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ __('manager.no_maps_found_text') }}</p>
                </div>
        @endif
        @foreach ($maps as $map)
            <div
                class=" bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 col-span-3">
                <a>
                    <img class="rounded-t-lg" src="{{ $map->getImageUrlAttribute() }}" alt="{{ $map->name }}" />
                </a>
                <div class="p-5">
                    <a>
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $map->name }}
                        </h5>
                    </a>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $map->map_code }}</p>
                    <button type="button" wire:click='confirmDelete({{ $map->id }})'
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 cursor-pointer"><i
                            class="fa-solid fa-trash" wire:target="delete({{ $map->id }})" wire:loading.class="fa-spinner fa-spin" wire:loading.class.remove="fa-trash"></i> {{ __('manager.remove') }}</button>

                </div>
            </div>
        @endforeach
    </div>

    <div id="add-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('manager.add_map') }}
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="add-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form wire:submit="add">

                        <div class="mb-5">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('name') dark:text-red-500! @enderror">{{ __('manager.map_name') }}
                                @error('name')
                                    ({{ $message }})
                                @enderror
                            </label>
                            <input wire:model.blur='name' type="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Dust 2" required />
                        </div>
                        <div class="mb-5">
                            <label for="map_code"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('map_code') dark:text-red-500! @enderror">{{ __('manager.map_code') }}
                                @error('map_code')
                                    ({{ $message }})
                                @enderror
                            </label>
                            <input wire:model.blur='map_code' type="map_code" id="map_code"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('manager.map_code_placeholder') }}" required />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('manager.map_code_help') }}</p>
                        </div>
                        <div class="mb-5">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="mapThumbnail">{{ __('manager.map_thumbnail') }}</label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                id="mapThumbnail" wire:model='mapThumbnail' type="file">
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('manager.map_thumbnail_help') }} <a href="https://github.com/ghostcap-gaming/cs2-map-images/tree/main/cs2" target="_blank" class="text-blue-500 hover:underline">repository</a> </p>
                        </div>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" wire:loading.attr="disabled"
                        class="cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        {{ __('manager.add') }}
                        <i class="fa-solid fa-spinner fa-spin" wire:loading.delay.longer></i>
                    </button>
                    </form>
                    <button data-modal-hide="add-modal" type="button"
                        class="cursor-pointer py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">{{ __('manager.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $wire.on('mapAdded', () => {
            const modal = new Modal(document.getElementById('add-modal'));
            if (modal) {
                modal.hide();
                document.querySelector("body > div[modal-backdrop]")?.remove();
            }
        });
    </script>
@endscript
