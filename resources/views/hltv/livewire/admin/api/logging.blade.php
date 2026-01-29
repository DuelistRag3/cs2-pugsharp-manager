<div>
    <div id="accordion-collapse" data-accordion="collapse"
        class="rounded-base border border-default overflow-hidden shadow-xs">
        @foreach($logs as $log)
            <h2 id="accordion-collapse-heading-{{ $log->id }}">
                <button type="button"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-body rounded-t-base border border-t-0 border-x-0 border-b-default hover:text-heading hover:bg-neutral-secondary-medium gap-3"
                    data-accordion-target="#accordion-collapse-body-{{ $log->id }}" aria-expanded="true"
                    aria-controls="accordion-collapse-body-{{ $log->id }}">
                    <span>{{ $log->type }} - {{ $log->created_at }}</span>
                    <svg data-accordion-icon class="w-5 h-5 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m5 15 7-7 7 7" />
                    </svg>
                </button>
            </h2>
            <div id="accordion-collapse-body-{{ $log->id }}" class="hidden"
                aria-labelledby="accordion-collapse-heading-{{ $log->id }}">
                <div class="p-5 border border-t-0 border-default bg-neutral-secondary-light">
                    <pre>{{ $log->payload }}</pre>
                </div>
            </div>
        @endforeach
    </div>

</div>
