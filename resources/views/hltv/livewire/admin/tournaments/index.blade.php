<div>
    <button type="button" data-modal-target="create-modal" data-modal-toggle="create-modal"
        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer"><i
            class="fa-solid fa-plus"></i> {{ __('manager.add') }}</button>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-2">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.name') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.status') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.registration_deadline') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.start_date') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.teams') }} / {{ __('manager.max_teams') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($tournaments->isEmpty())
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        {{ __('manager.no_tournaments') }}
                    </td>
                </tr>
                @else
                @foreach ($tournaments as $tournament)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $tournament->id }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $tournament->name }}
                    </td>
                    <td class="px-6 py-4">
                        @switch($tournament->status)
                        @case('scheduled')
                        <span class="text-yellow-500">{{ __('manager.status_types.scheduled') }}</span>
                        @break

                        @case('ongoing')
                        <span class="text-blue-500">{{ __('manager.status_types.ongoing') }}</span>
                        @break

                        @case('completed')
                        <span class="text-green-500">{{ __('manager.status_types.completed') }}</span>
                        @break

                        @case('cancelled')
                        <span class="text-red-500">{{ __('manager.status_types.cancelled') }}</span>
                        @break

                        @default
                        <span class="text-gray-500">{{ __('manager.status_types.unknown') }}</span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4">
                        {{ $tournament->registration_deadline ? new
                        DateTime($tournament->registration_deadline)->format('d.m.Y H:i') : __('manager.start_date') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $tournament->start_date ? new DateTime($tournament->start_date)->format('d.m.Y H:i') : '' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $tournament->teams->count() ?? 0 }} / {{ $tournament->max_teams }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" wire:navigate
                                class="text-white text-xs bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <i class="fa-solid fa-eye"></i>
                                <span class="sr-only">{{ __('manager.view') }}</span>
                            </a>
                            <button wire:click="delete({{ $tournament->id }})"
                                class="cursor-pointer text-xs text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                <i class="fa-solid fa-trash"></i>
                                <span class="sr-only">{{ __('manager.delete') }}</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>

        </table>
        @if ($tournaments)
        {{ $tournaments->links() }}
        @endif
    </div>

    <div id="create-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('manager.create_tournament') }}
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="create-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form wire:submit="create" class="">

                        <div class="mb-5">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('name') dark:text-red-500! @enderror">{{ __('manager.name') }}
                                @error('name') ({{ $message }}) @enderror</label>
                            <input wire:model.blur='name' type="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('manager.name') }}" required />

                        </div>
                        <div class="mb-5">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white  @error('description') dark:text-red-500! @enderror">{{ __('manager.description') }}
                                @error('description') ({{ $message }}) @enderror</label>
                            <textarea wire:model.blur='description' id="description"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('manager.description') }}"></textarea>
                        </div>
                        <div class="mb-5">
                            <label for="start_date"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('start_date') dark:text-red-500! @enderror">{{ __('manager.start_date') }}
                                @error('start_date') ({{ $message }}) @enderror</label>
                            <input wire:model.blur='start_date' type="datetime-local" id="start_date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('manager.start_date') }}" required />
                        </div>
                        <div class="mb-5">
                            <label for="registration_deadline"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('registration_deadline') dark:text-red-500! @enderror">{{ __('manager.registration_deadline') }}
                                ({{ __('manager.optional') }}) @error('registration_deadline') ({{ $message }}) @enderror</label>
                            <input wire:model.blur='registration_deadline' type="datetime-local"
                                id="registration_deadline"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('manager.registration_deadline') }}" />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('manager.registration_deadline_help') }}</p>
                        </div>
                        <div class="mb-5">
                            <label for="team_size"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('team_size') dark:text-red-500! @enderror">{{ __('manager.team_size') }}
                                @error('team_size') ({{ $message }}) @enderror</label>
                            <select wire:model.blur='team_size' id="team_size"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="1">1 {{ __('manager.player') }} (1v1)</option>
                                <option value="2">2 {{ __('manager.player') }} (2v2)</option>
                                <option value="3">3 {{ __('manager.player') }} (3v3)</option>
                                <option value="4">4 {{ __('manager.player') }} (4v4)</option>
                                <option value="5" selected>5 {{ __('manager.player') }} (5v5)</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="max_teams"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('max_teams') dark:text-red-500! @enderror">{{ __('manager.max_teams') }} @error('max_teams') ({{ $message }}) @enderror</label>
                            <input wire:model.blur='max_teams' type="number" id="max_teams"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="mb-5">
                            <label for="maps_each_game"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('maps_each_game') dark:text-red-500! @enderror">{{ __('manager.gametype') }}
                                @error('maps_each_game') ({{ $message }}) @enderror</label>
                            <select wire:model.blur='maps_each_game' id="maps_each_game"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="1">{{ __('manager.best_of_1') }}</option>
                                <option value="3">{{ __('manager.best_of_3') }}</option>
                                <option value="5">{{ __('manager.best_of_5') }}</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="maps_final_game"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('maps_final_game') dark:text-red-500! @enderror">{{ __('manager.gametype') }} ({{ __('manager.final') }})
                                @error('maps_final_game') ({{ $message }}) @enderror</label>
                            <select wire:model.blur='maps_final_game' id="maps_final_game"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="1">{{ __('manager.best_of_1') }}</option>
                                <option value="3">{{ __('manager.best_of_3') }}</option>
                                <option value="5">{{ __('manager.best_of_5') }}</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="map_rounds"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('map_rounds') dark:text-red-500! @enderror">{{ __('manager.rounds_per_map') }} @error('map_rounds') ({{ $message }}) @enderror</label>
                            <input wire:model.blur='map_rounds' type="number" id="map_rounds"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Runden pro Match" required />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('manager.rounds_per_map_help') }}</p>
                        </div>
                        <div class="mb-5">
                            <label for="map_overtime_rounds"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('map_overtime_rounds') dark:text-red-500! @enderror">{{ __('manager.overtime_rounds') }}
                                @error('map_overtime_rounds') ({{ $message }}) @enderror</label>
                            <input wire:model.blur='map_overtime_rounds' type="number" id="map_overtime_rounds"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Overtime-Runden" required />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('manager.overtime_rounds_help') }}</p>
                        </div>
                        <div class="mb-5">
                            <label for="guest_mode"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('guest_mode') dark:text-red-500! @enderror">{{ __('manager.guest_mode') }}
                                @error('guest_mode') ({{ $message }}) @enderror</label>
                            <select wire:model.blur='guest_mode' id="guest_mode"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">{{ __('manager.disabled') }}</option>
                                <option value="1">{{ __('manager.enabled') }}</option>
                            </select>
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('manager.guest_mode_help') }}</p>
                        </div>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit"
                        class="cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('manager.create_tournament') }}</button>
                    </form>
                    <button data-modal-hide="create-modal" type="button"
                        class="cursor-pointer py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">{{ __('manager.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('tournamentCreated', () => {
            const modal = new Modal(document.getElementById('create-modal'));
            if (modal) {
                modal.hide();
                document.querySelector("body > div[modal-backdrop]")?.remove();
            }
        });
</script>
@endscript