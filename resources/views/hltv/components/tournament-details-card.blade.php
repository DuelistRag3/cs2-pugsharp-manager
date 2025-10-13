<div class="bg-gray-800 text-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-2">{{ __('manager.details') }}</h2>
    @if($tournament->guest_mode)
    <p class="text-sm text-gray-400">{{ __('manager.guest_mode') }}</p>
    @endif
    <p><strong>{{ __('manager.registration_deadline') }}:</strong>
        @if ($tournament->registration_deadline)
        {{ new DateTime($tournament->registration_deadline)->format(__('manager.timeformat')) }}
        @else
        {{ new DateTime($tournament->start_date)->format(__('manager.timeformat')) }}
        @endif
    </p>
    <p><strong>{{ __('manager.start_date') }}:</strong> {{ new DateTime($tournament->start_date)->format(__('manager.timeformat')) }}</p>
    <p><strong>{{ __('manager.end_date') }}:</strong> 
        @if(!$tournament->status == 'completed' || !$tournament->status == 'cancelled')
            {{ __('manager.tournament_not_finished') }} 
        @else 
            {{ new DateTime($tournament->end_date)->format(__('manager.timeformat')) }} 
        @endif
    </p>
    <p><strong>{{ __('manager.max_teams') }}:</strong> {{ $tournament->max_teams }}</p>
    <p><strong>{{ __('manager.team_size') }}:</strong> {{ $tournament->team_size }}</p>
    <p><strong>{{ __('manager.gametype') }}:</strong>
        <span class="text-blue-500">{{ __('manager.best_of_' . $tournament->maps_each_game) }}</span>
    </p>
    <p><strong>{{ __('manager.gametype') }} ({{ __('manager.final') }}):</strong>
        <span class="text-blue-500">{{ __('manager.best_of_' . $tournament->maps_final_game) }}</span>
    </p>
    <p><strong>{{ __('manager.status') }}:</strong>
        <span class="text-{{ config('manager.status_colors.' . $tournament->status) }}-500">{{ __('manager.status_types.' . $tournament->status) }}</span>
    </p>
    @if($tournament->status == 'completed')
        <p><strong>{{ __('manager.winner') }}:</strong> {{ $tournament->games()->where('next_game_id', null)->first()->winnerTeam->name }}</p>
    @endif
</div>