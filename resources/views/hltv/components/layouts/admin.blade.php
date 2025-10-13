<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/js/app.js'])
    @vite(['resources/css/app.css'])

    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/images/cs2logo.ico') }}">
    @livewireStyles
</head>
<body class="dark:bg-gray-900 text-black dark:text-white">
    <x-navigation.sidebar>
        {{ $slot }}
    </x-navigation.sidebar>

    @livewireScripts
</body>
</html>