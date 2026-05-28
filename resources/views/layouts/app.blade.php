<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white dark:bg-zinc-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FunShirt') }} @yield('title')</title>

    <!-- Fonts & Modern Tailwind Processing via Vite -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <flux:theme mode="system" />
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 antialiased text-zinc-900 dark:text-zinc-50">
    
    <!-- Dynamic Alert Session Notifications banner -->
    @if (session('alert-success'))
        <div class="bg-emerald-600 text-white px-4 py-3 text-center text-sm font-medium relative shadow-sm z-50">
            {{ session('alert-success') }}
        </div>
    @endif
    @if (session('alert-danger'))
        <div class="bg-rose-600 text-white px-4 py-3 text-center text-sm font-medium relative shadow-sm z-50">
            {{ session('alert-danger') }}
        </div>
    @endif

    <div class="flex min-h-screen flex-col lg:flex-row">
        <!-- Sidebar Navigation Menu Container -->
        <x-layouts.navigation />

        <!-- Main Body Structural Frame Layout -->
        <main class="flex-1 p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    @fluxScripts
</body>
</html>