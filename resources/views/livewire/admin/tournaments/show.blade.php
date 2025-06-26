<div>
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white">Tournament: {{ $tournament->name }}</h1>
            <div class="right">
                <button type="button" data-modal-target="create-modal" data-modal-toggle="create-modal"
                    class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 cursor-pointer"><i
                        class="fa-solid fa-plus"></i> Bearbeiten</button>
                <button wire:click='createFirstRound()' type="button"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer">Erste
                    runde Starten</button>
            </div>
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">Details</h2>
            <p><strong>Start Datum:</strong> {{ new DateTime($tournament->start_date)->format('d.m.Y H:i') }}</p>
            <p><strong>End Datum:</strong> {{ new DateTime($tournament->end_date)->format('d.m.Y H:i') }}</p>
            <p><strong>Status:</strong>
                @switch($tournament->status)
                @case('scheduled')
                <span class="text-yellow-500">Geplant</span>
                @break
                @case('ongoing')
                <span class="text-green-500">Läuft</span>
                @break
                @case('completed')
                <span class="text-gray-500">Abgeschlossen</span>
                @break
                @case('cancelled')
                <span class="text-red-500">Abgebrochen</span>
                @default
                <span class="text-red-500">Unbekannt</span>
                @endswitch
            </p>
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">Teams</h2>
            @if($tournament->teams->isEmpty())
            <p>Bisher sind keine Teams registriert.</p>
            @else
            <ul class="list-disc">
                @foreach($tournament->teams as $team)
                <button data-modal-target="team{{ $team->id }}-modal" data-modal-toggle="team{{ $team->id }}-modal"
                    class="bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center mt-2">{{
                    $team->name }}</button>
                @endforeach
            </ul>

            @endif
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">Matches</h2>
            @if($tournament->games->isEmpty())
            <p>Hier wird der Matchplan stehen.</p>
            @else
            <ul class="list-disc pl-5">
                @foreach($tournament->games as $match)
                <div
                    class="max-w-48 text-sm font-medium mb-2 text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <a href="#" aria-current="true"
                        class="block w-full px-4 py-2 border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                        {{ $match->team1->name }}
                    </a>
                    <a href="#"
                        class="block w-full px-4 py-2 rounded-b-lg cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                        {{ $match->team2->name }}
                    </a>
                </div>
                @endforeach
            </ul>
            @endif
        </div>


        {{-- team modals --}}
        @foreach($tournament->teams as $team)
        <div id="team{{ $team->id }}-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                {{--
                <!-- Modal content --> --}}
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    {{--
                    <!-- Modal header --> --}}
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $team->name }} - {{ $team->tag }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="team1-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Schließen</span>
                        </button>
                    </div>
                    {{--
                    <!-- Modal body --> --}}
                    <div class="p-4 md:p-5 space-y-4">
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($team->players as $player)
                            <li class="py-3 sm:py-4">
                                <div class="flex items-center">
                                    <div class="shrink-0">
                                        <img class="w-8 h-8 rounded-full" src="{{ $player->steam_avatar }}"
                                            alt="{{ $player->name }}">
                                    </div>
                                    <div class="flex-1 min-w-0 ms-4">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-blue-400">
                                            <a href="{{ $player->steam_url }}" target="_blank">{{ $player->steam_name
                                                }}</a>
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            Steam ID: {{ $player->steam_id }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    {{--
                    <!-- Modal footer --> --}}
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="team1-modal" type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Schließen</button>

                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>