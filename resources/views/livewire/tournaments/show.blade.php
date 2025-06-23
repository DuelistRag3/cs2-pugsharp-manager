<div>
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white">Turnier: {{ $tournament->name }}</h1>
            <button type="button" data-modal-target="register-modal" data-modal-toggle="register-modal"
        class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 cursor-pointer">Anmelden</button>
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
            @if(!$tournament->teams->isEmpty())
                <p>Bisher sind keine Teams registriert.</p>
            @else
                <ul class="list-disc pl-5">
                    @foreach($tournament->teams() as $team)
                        <a href="#" class="bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center">{{ $team->name }}</a>
                        <a href="#" class="bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center">Badge link</a>
                    @endforeach
                </ul>
                <button data-modal-target="team1-modal" data-modal-toggle="team1-modal" class="bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center cursor-pointer">Team 1</button>
                <button data-modal-target="team2-modal" data-modal-toggle="team2-modal" class="bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center">Team 2</button>
            @endif
        </div>

        <div class="bg-gray-800 text-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-2">Matches</h2>
            @if($tournament->games->isEmpty())
                <p>Hier wird der Matchplan stehen.</p>
            @else
                <ul class="list-disc pl-5">
                    @foreach($tournament->games as $match)
                        <li>{{ $match->team1->name }} vs {{ $match->team2->name }} - {{ $match->scheduled_at->format('d.m.Y H:i') }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

{{-- team modals --}}
        <div id="team1-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        {{-- <!-- Modal content --> --}}
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            {{-- <!-- Modal header --> --}}
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Teamname
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="team1-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Schließen</span>
                </button>
            </div>
            {{-- <!-- Modal body --> --}}
            <div class="p-4 md:p-5 space-y-4">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    
                </ul>
            </div>
            {{-- <!-- Modal footer --> --}}
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="team1-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Schließen</button>
                
            </div>
        </div>
    </div>
</div>

<!-- Register modal -->
<div id="register-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Melde dich für das Turnier an
                </h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Schließen</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form wire:submit='registerTeam' class="space-y-4" action="#">
                    <div>
                        <label for="teamname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teamname</label>
                        <input wire:model='teamname' type="text" name="teamname" id="teamname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Teamname" required />
                    </div>
                    <div>
                        <label for="player1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Steam ID von Spieler 1</label>
                        <input type="text" name="player1" id="player1" wire:model='player1Id' placeholder="Steam ID von Spieler 1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                    </div>
                    <div>
                        <label for="player2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Steam ID von Spieler 2</label>
                        <input type="text" name="player2" id="player2" wire:model='player2Id' placeholder="Steam ID von Spieler 1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                    </div>
                    <div>
                        <label for="player3" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Steam ID von Spieler 3</label>
                        <input type="text" name="player3" id="player3" wire:model='player3Id' placeholder="Steam ID von Spieler 1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                    </div>
                    <div>
                        <label for="player4" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Steam ID von Spieler 4</label>
                        <input type="text" name="player4" id="player4" wire:model='player4Id' placeholder="Steam ID von Spieler 1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                    </div>
                    <div>
                        <label for="player5" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Steam ID von Spieler 5</label>
                        <input type="text" name="player5" id="player5" wire:model='player5Id' placeholder="Steam ID von Spieler 1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Anmelden</button>
                </form>
            </div>
        </div>
    </div>
</div> 


    </div>    
</div>

@script
    <script>
        $wire.on('teamRegistered', () => {
            const modal = new Modal(document.getElementById('register-modal'));
            if (modal) {
                modal.hide();
                document.querySelector("body > div[modal-backdrop]")?.remove();
            }
        });
    </script>
@endscript