<div class="grid auto-rows-auto max-w-6/12 mx-auto" wire:poll>
    <div>
        <h1 class="text-2xl text-bold">{{ __('manager.running_matches') }}</h1>
        @foreach($runningMatches as $match)
            <x-match-card :match=$match />
        @endforeach
    </div>
    <div>
        <h1 class="text-2xl text-bold">{{ __('manager.upcoming_matches') }}</h1>
        @foreach($upcomingMatches as $match)
            <x-match-card :match=$match />
        @endforeach
    </div>
</div>
