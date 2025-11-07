<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" href="https://fav.farm/🔥" />

        <title>{{ $title ?? 'HelpSpot Diet' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxStyles
    </head>
    <body class="min-h-screen bg-gray-50">
        <flux:header container>
            <flux:navbar>
                <a href="{{ route('tickets.index') }}" class="text-xl font-bold text-gray-900">
                    HelpSpot Diet
                </a>

                <flux:navlist>
                    <flux:navlist.item href="{{ route('tickets.index') }}">Tickets</flux:navlist.item>
                </flux:navlist>
            </flux:navbar>
        </flux:header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

        @fluxScripts
    </body>
</html>
