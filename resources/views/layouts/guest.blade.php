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
                --guest-bg: #f4f7fb;
                --guest-surface: rgba(255, 255, 255, 0.96);
                --guest-surface-soft: #eef2ff;
                --guest-border: rgba(226, 232, 240, 0.95);
                --guest-text: #0f172a;
                --guest-muted: #475569;
                --guest-soft: #64748b;
                --guest-accent: #4f46e5;
                --guest-header: rgba(255, 255, 255, 0.8);
                --guest-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            :root[data-theme="dark"] {
                color-scheme: dark;
                --guest-bg: #09090b;
                --guest-surface: rgba(24, 24, 27, 0.94);
                --guest-surface-soft: #27272a;
                --guest-border: rgba(63, 63, 70, 0.95);
                --guest-text: #fafafa;
                --guest-muted: #d4d4d8;
                --guest-soft: #a1a1aa;
                --guest-accent: #818cf8;
                --guest-header: rgba(24, 24, 27, 0.84);
                --guest-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
            }
        </style>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root[data-theme="dark"] body .bg-white {
                background-color: var(--guest-surface) !important;
            }

            :root[data-theme="dark"] body .bg-zinc-50,
            :root[data-theme="dark"] body .bg-gray-50,
            :root[data-theme="dark"] body .bg-gray-100 {
                background-color: var(--guest-surface-soft) !important;
            }

            :root[data-theme="dark"] body .border-zinc-200,
            :root[data-theme="dark"] body .border-zinc-300,
            :root[data-theme="dark"] body .border-gray-200,
            :root[data-theme="dark"] body .border-gray-300 {
                border-color: var(--guest-border) !important;
            }

            :root[data-theme="dark"] body .text-zinc-900,
            :root[data-theme="dark"] body .text-gray-900,
            :root[data-theme="dark"] body .text-gray-800,
            :root[data-theme="dark"] body .text-zinc-700 {
                color: var(--guest-text) !important;
            }

            :root[data-theme="dark"] body .text-zinc-600,
            :root[data-theme="dark"] body .text-zinc-500,
            :root[data-theme="dark"] body .text-gray-600,
            :root[data-theme="dark"] body .text-gray-500 {
                color: var(--guest-muted) !important;
            }

            :root[data-theme="dark"] body .shadow-md,
            :root[data-theme="dark"] body .shadow-xl,
            :root[data-theme="dark"] body .shadow-sm {
                box-shadow: var(--guest-shadow) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased" style="background: var(--guest-bg); color: var(--guest-text);">
        <div class="min-h-screen" style="background: var(--guest-bg); color: var(--guest-text);">
            <livewire:layout.navigation />

            <main class="mx-auto flex min-h-[calc(100vh-64px)] w-full max-w-7xl items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
                <div class="w-full max-w-2xl rounded-3xl border p-6 shadow-xl sm:p-8" style="background: var(--guest-surface); border-color: var(--guest-border); box-shadow: var(--guest-shadow);">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
