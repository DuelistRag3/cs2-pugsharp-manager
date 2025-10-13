<li class="py-3 sm:py-4">
    <div class="flex items-center">
        <div class="shrink-0">
            <img class="w-8 h-8 rounded-full" src="{{ $steamavatar }}" alt="avatar">
        </div>
        <div class="flex-1 min-w-0 ms-4">
            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                <a class="text-blue-500" href="{{ $steamlink }}" target="_blank">{{ $steamname }}</a>
            </p>
            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                {{ $id }}
            </p>
        </div>
    </div>
</li>
