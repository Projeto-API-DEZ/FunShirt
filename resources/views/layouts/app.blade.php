<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <script>
            (() => {
                const readTheme = () => {
                    const stored = localStorage.getItem('funshirt-theme');
                    const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    return stored ?? (systemDark ? 'dark' : 'light');
                };

                window.funshirtApplyTheme = (theme = readTheme()) => {
                    document.documentElement.dataset.theme = theme;
                    localStorage.setItem('funshirt-theme', theme);
                    document.dispatchEvent(new CustomEvent('funshirt-theme-changed', { detail: { theme } }));
                };

                window.funshirtReadTheme = readTheme;
                window.funshirtApplyTheme(readTheme());
                document.addEventListener('DOMContentLoaded', () => window.funshirtApplyTheme(readTheme()));
                document.addEventListener('livewire:navigated', () => window.funshirtApplyTheme(readTheme()));
                window.addEventListener('pageshow', () => window.funshirtApplyTheme(readTheme()));
            })();
        </script>
        <style>
            :root {
                color-scheme: light;
                --app-bg: #f4f4f5;
                --app-surface: #ffffff;
                --app-surface-2: #fafafa;
                --app-border: #e4e4e7;
                --app-text: #18181b;
                --app-muted: #52525b;
                --app-nav: rgba(255, 255, 255, 0.88);
                --app-shadow: 0 20px 45px rgba(15, 23, 42, 0.06);
            }

            :root[data-theme="dark"] {
                color-scheme: dark;
                --app-bg: #09090b;
                --app-surface: #18181b;
                --app-surface-2: #27272a;
                --app-border: #3f3f46;
                --app-text: #fafafa;
                --app-muted: #a1a1aa;
                --app-nav: rgba(24, 24, 27, 0.84);
                --app-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
            }
        </style>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root[data-theme="dark"] body .bg-white {
                background-color: var(--app-surface) !important;
            }

            :root[data-theme="dark"] body .bg-gray-50,
            :root[data-theme="dark"] body .bg-gray-100,
            :root[data-theme="dark"] body .bg-zinc-50 {
                background-color: var(--app-surface-2) !important;
            }

            :root[data-theme="dark"] body .border-gray-100,
            :root[data-theme="dark"] body .border-gray-200,
            :root[data-theme="dark"] body .border-zinc-200,
            :root[data-theme="dark"] body .border-zinc-300 {
                border-color: var(--app-border) !important;
            }

            :root[data-theme="dark"] body .text-gray-900,
            :root[data-theme="dark"] body .text-gray-800,
            :root[data-theme="dark"] body .text-zinc-900,
            :root[data-theme="dark"] body .text-zinc-700 {
                color: var(--app-text) !important;
            }

            :root[data-theme="dark"] body .text-gray-600,
            :root[data-theme="dark"] body .text-gray-500,
            :root[data-theme="dark"] body .text-zinc-600,
            :root[data-theme="dark"] body .text-zinc-500 {
                color: var(--app-muted) !important;
            }

            :root[data-theme="dark"] body input,
            :root[data-theme="dark"] body textarea,
            :root[data-theme="dark"] body select {
                background-color: var(--app-surface-2) !important;
                color: var(--app-text) !important;
                border-color: var(--app-border) !important;
            }

            :root[data-theme="dark"] body input::placeholder,
            :root[data-theme="dark"] body textarea::placeholder {
                color: var(--app-muted) !important;
            }

            :root[data-theme="dark"] body .shadow,
            :root[data-theme="dark"] body .shadow-sm,
            :root[data-theme="dark"] body .shadow-md,
            :root[data-theme="dark"] body .shadow-lg {
                box-shadow: var(--app-shadow) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased" style="background: var(--app-bg); color: var(--app-text);">
        <div class="min-h-screen" style="background: var(--app-bg); color: var(--app-text);">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="border-b shadow-sm" style="background: var(--app-surface); border-color: var(--app-border); box-shadow: var(--app-shadow);">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="{{ asset('js/preview.js') }}"></script>
    </body>
</html>
