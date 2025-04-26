<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $metaDescription ?? 'Luxury Hotel Booking - Your perfect stay' }}">

        <title>{{ config('app.name', 'Luxury Hotel') }} - {{ $title ?? 'Welcome' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:100,200,300,400,500,600,700,800,900" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Extra Styles -->
        {{ $styles ?? '' }}
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col">
            <x-partials.header />

            <!-- Main content area -->
            <main class="flex-grow py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>

            <x-partials.footer />
        </div>

        <!-- Extra Scripts -->
        {{ $scripts ?? '' }}
    </body>
</html>
