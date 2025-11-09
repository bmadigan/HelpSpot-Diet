<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" href="https://fav.farm/🔥" />

        <title>{{ $title ?? 'HelpSpot Diet' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-gray-50 dark:bg-zinc-900">
        <flux:accent color="blue">
            <flux:header container>
            <flux:navbar class="border-b border-zinc-200 dark:border-white/10">
                <flux:brand name="HelpSpot Diet" href="{{ route('tickets.index') }}" />

                <flux:navlist class="ms-auto flex-row items-center gap-2">
                    <flux:navlist.item href="{{ route('dashboard') }}">Dashboard</flux:navlist.item>
                    <flux:navlist.item href="{{ route('tickets.index') }}">Tickets</flux:navlist.item>
                </flux:navlist>
            </flux:navbar>
        </flux:header>

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </main>

            @fluxScripts
        </flux:accent>
    </body>
</html>
