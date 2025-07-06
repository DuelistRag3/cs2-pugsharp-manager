<div>
    <button type="button" data-modal-target="create-modal" data-modal-toggle="create-modal"
        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer"><i
            class="fa-solid fa-plus"></i> Erstellen</button>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-2">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Anmeldeschluss
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Startzeit
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Teams / Max Teams
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Aktionen
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($tournaments->isEmpty())
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Keine Turniere gefunden.
                        </td>
                    </tr>
                @else
                    @foreach ($tournaments as $tournament)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $tournament->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $tournament->name }}
                            </td>
                            <td class="px-6 py-4">
                                @switch($tournament->status)
                                    @case('scheduled')
                                        <span class="text-yellow-500">Geplant</span>
                                    @break

                                    @case('ongoing')
                                        <span class="text-blue-500">Laufend</span>
                                    @break

                                    @case('completed')
                                        <span class="text-green-500">Abgeschlossen</span>
                                    @break

                                    @case('cancelled')
                                        <span class="text-red-500">Abgebrochen</span>
                                    @break

                                    @default
                                        <span class="text-gray-500">Unbekannt</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                {{ $tournament->registration_deadline ? new DateTime($tournament->registration_deadline)->format('d.m.Y H:i') : 'Turnierstart' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $tournament->start_date ? new DateTime($tournament->start_date)->format('d.m.Y H:i') : 'Nicht festgelegt' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $tournament->teams->count() ?? 0 }} / {{ $tournament->max_teams }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.tournaments.show', $tournament->id) }}"
                                        class="text-white text-xs bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <i class="fa-solid fa-eye"></i>
                                        <span class="sr-only">Ansehen</span>
                                    </a>
                                    <button wire:click="delete({{ $tournament->id }})"
                                        class="cursor-pointer text-xs text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        <i class="fa-solid fa-trash"></i>
                                        <span class="sr-only">Löschen</span>
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

    <!-- Create modal -->
    <div id="create-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Turnier erstellen
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
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <form wire:submit="create" class="">

                        <div class="mb-5">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Turniername</label>
                            <input wire:model='name' type="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Turniername" required />
                        </div>
                        <div class="mb-5">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Beschreibung</label>
                            <textarea wire:model='description' id="description"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Beschreibung" required></textarea>
                        </div>
                        <div class="mb-5">
                            <label for="registration_deadline"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anmeldeschluss (Optional)</label>
                            <input wire:model='registration_deadline' type="datetime-local" id="registration_deadline"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Anmeldeschluss" />
                                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Wenn nicht angegeben endet die Registrierung mit Turnierbeginn.</p>
                        </div>
                        <div class="mb-5">
                            <label for="start_date"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Startdatum</label>
                            <input wire:model='start_date' type="datetime-local" id="start_date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Startdatum" required />
                        </div>
                        <div class="mb-5">
                            <label for="team_size"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teamgröße</label>
                            <select wire:model='team_size' id="team_size"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="1">1 Spieler (1v1)</option>
                                <option value="2">2 Spieler (2v2)</option>
                                <option value="3">3 Spieler (3v3)</option>
                                <option value="4">4 Spieler (4v4)</option>
                                <option value="5" selected>5 Spieler (5v5)</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="max_teams"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Maximale
                                Teams</label>
                            <input wire:model='max_teams' type="number" id="max_teams"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                 required />
                        </div>
                        <div class="mb-5">
                            <label for="matchup_rounds"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Matchup-Runden</label>
                            <select wire:model='matchup_rounds' id="matchup_rounds"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">Best of 1</option>
                                <option value="1">Best of 3</option>
                                <option value="2">Best of 5</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="final_rounds"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Final-Runden</label>
                            <select wire:model='final_rounds' id="final_rounds"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">Best of 1</option>
                                <option value="1">Best of 3</option>
                                <option value="2">Best of 5</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="match_rounds"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Runden pro Match</label>
                            <input wire:model='match_rounds' type="number" id="match_rounds"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Runden pro Match" required />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Standardmäßig 24 Runden für CS2.</p>
                        </div>
                        <div class="mb-5">
                            <label for="overtime_rounds"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Overtime-Runden</label>
                            <input wire:model='overtime_rounds' type="number" id="overtime_rounds"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Overtime-Runden" required />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Standardmäßig 6 Overtime-Runden für CS2.</p>
                        </div>
                        
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit"
                        class="cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Erstellen</button>
                    </form>
                    <button data-modal-hide="create-modal" type="button"
                        class="cursor-pointer py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Abbrechen</button>
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
            // Optionally, you can refresh the tournaments list or show a success message
            // Livewire.emit('refreshTournaments');
        });
    </script>
@endscript
