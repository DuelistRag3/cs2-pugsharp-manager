@php
$classes = ($active ?? false)
    ? 'text-blue-700 dark:text-blue-500'
    : 'text-gray-900 dark:text-white';
@endphp

<li>
    <a wire:navigate href="{{ $attributes->get('href') }}"
        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $classes }}">
        <i class="{{ $attributes->get('icon') }}"></i>
        <span class="ms-3">{{ $slot }}</span>
    </a>
</li>
