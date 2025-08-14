<div class="bg-gray-800 text-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-2">{{ __('manager.details') }}</h2>
    <p><strong>{{ __('manager.registration_deadline') }}:</strong>
        @if ($tournament->registration_deadline)
        {{ new DateTime($tournament->registration_deadline)->format('d.m.Y H:i') }}
        @else
        {{ new DateTime($tournament->start_date)->format('d.m.Y H:i') }}
        @endif
    </p>
    <p><strong>{{ __('manager.start_date') }}:</strong> {{ new DateTime($tournament->start_date)->format('d.m.Y
        H:i') }}</p>
    <p><strong>{{ __('manager.end_date') }}:</strong> @if($tournament->status != 'completed' ||
        $tournament->status != 'cancelled')
        {{ __('manager.tournament_not_finished') }} @else {{ new DateTime($tournament->end_date)->format('d.m.Y
        H:i') }} @endif
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
        @switch($tournament->status)
        @case('scheduled')
        <span class="text-yellow-500">{{ __('manager.status_types.scheduled') }}</span>
        @break
        @case('ongoing')
        <span class="text-green-500">{{ __('manager.status_types.ongoing') }}</span>
        @break
        @case('completed')
        <span class="text-gray-500">{{ __('manager.status_types.completed') }}</span>
        @break
        @case('cancelled')
        <span class="text-red-500">{{ __('manager.status_types.cancelled') }}</span>
        @default
        <span class="text-red-500">{{ __('manager.status_types.unknown') }}</span>
        @endswitch
    </p>
</div>