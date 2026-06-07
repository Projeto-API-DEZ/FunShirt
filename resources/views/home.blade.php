<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>FunShirt - Loja Online</title>

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
                --home-bg: #f4f7fb;
                --home-hero-1: #eef2ff;
                --home-hero-2: #ffffff;
                --home-accent: #4f46e5;
                --home-accent-2: #7c3aed;
                --home-surface: rgba(255, 255, 255, 0.94);
                --home-surface-soft: #f8fafc;
                --home-border: rgba(226, 232, 240, 0.95);
                --home-text: #0f172a;
                --home-muted: #475569;
                --home-soft: #64748b;
                --home-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            :root[data-theme="dark"] {
                color-scheme: dark;
                --home-bg: #09090b;
                --home-hero-1: #18181b;
                --home-hero-2: #09090b;
                --home-accent: #818cf8;
                --home-accent-2: #a78bfa;
                --home-surface: rgba(24, 24, 27, 0.94);
                --home-surface-soft: #27272a;
                --home-border: rgba(63, 63, 70, 0.95);
                --home-text: #fafafa;
                --home-muted: #d4d4d8;
                --home-soft: #a1a1aa;
                --home-shadow: 0 24px 60px rgba(0, 0, 0, 0.4);
            }
        </style>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background: var(--home-bg); color: var(--home-text);">
        @guest
            <div class="min-h-screen" style="background: linear-gradient(180deg, var(--home-hero-1) 0%, var(--home-bg) 100%);">
                <header class="sticky top-0 z-40 border-b backdrop-blur" style="background: rgba(255,255,255,0.72); border-color: var(--home-border);">
                    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <a href="/" class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl text-sm font-semibold text-white" style="background: linear-gradient(135deg, var(--home-accent), var(--home-accent-2));">FS</div>
                                <div>
                                    <p class="text-sm font-semibold">FunShirt</p>
                                    <p class="text-xs" style="color: var(--home-soft);">Custom t-shirt storefront</p>
                                </div>
                            </a>

                            <nav class="hidden items-center gap-2 md:flex">
                                <a href="/" class="rounded-full px-3 py-2 text-sm font-medium transition hover:bg-black/5">Home</a>
                                <span class="rounded-full px-3 py-2 text-sm font-medium" style="background: var(--home-surface-soft); color: var(--home-muted);">Catalog</span>
                                <span class="rounded-full px-3 py-2 text-sm font-medium" style="background: var(--home-surface-soft); color: var(--home-muted);">Cart</span>
                                <span class="rounded-full px-3 py-2 text-sm font-medium" style="background: var(--home-surface-soft); color: var(--home-muted);">Orders</span>
                            </nav>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                id="theme-toggle-home"
                                aria-label="Toggle theme"
                                class="inline-flex items-center rounded-full border px-3 py-2 text-sm font-medium transition"
                                style="background: var(--home-surface); border-color: var(--home-border); color: var(--home-text);"
                            >
                                <span data-home-theme-label>Light</span>
                            </button>
                            <a href="{{ route('login') }}" class="inline-flex rounded-full px-4 py-2 text-sm font-medium transition hover:bg-black/5">Login</a>
                            <a href="{{ route('register') }}" class="inline-flex rounded-full px-4 py-2 text-sm font-semibold text-white shadow-sm transition" style="background: linear-gradient(135deg, var(--home-accent), var(--home-accent-2));">Register</a>
                        </div>
                    </div>
                </header>

                <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                    <section class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
                        <div class="max-w-3xl">
                            <p class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]" style="border-color: var(--home-border); color: var(--home-soft);">
                                Laravel project storefront
                            </p>
                            <h1 class="mt-6 text-5xl font-semibold tracking-tight sm:text-6xl">
                                Customize, order and manage printed t-shirts in one workflow.
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-8" style="color: var(--home-muted);">
                                FunShirt combines customer registration, session cart handling, order processing and internal management into one Laravel application. Start with authentication, then extend the remaining modules step by step.
                            </p>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full px-7 py-3 text-base font-semibold text-white shadow-sm transition" style="background: linear-gradient(135deg, var(--home-accent), var(--home-accent-2));">
                                    Create account
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border px-7 py-3 text-base font-semibold transition" style="border-color: var(--home-border); background: var(--home-surface);">
                                    Sign in
                                </a>
                            </div>
                        </div>

                        <div class="rounded-3xl border p-6 shadow-xl sm:p-8" style="background: var(--home-surface); border-color: var(--home-border); box-shadow: var(--home-shadow);">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium" style="color: var(--home-soft);">Current scope</p>
                                    <h2 class="mt-1 text-2xl font-semibold">Functional groups</h2>
                                </div>
                                <div class="rounded-2xl px-4 py-2 text-sm font-semibold" style="background: var(--home-surface-soft); color: var(--home-muted);">G1 live</div>
                            </div>

                            <div class="mt-6 space-y-3">
                                @foreach ([
                                    ['G1', 'Authentication & Users', 'Implemented'],
                                    ['G2', 'Catalog', 'Planned'],
                                    ['G3', 'Cart', 'Planned'],
                                    ['G4', 'Orders', 'Planned'],
                                    ['G5', 'Private Images', 'Planned'],
                                    ['G6', 'PDF & Email', 'Planned'],
                                    ['G7', 'Preview', 'Planned'],
                                    ['G8', 'Statistics', 'Planned'],
                                ] as [$group, $title, $state])
                                    <div class="flex items-center justify-between rounded-2xl border px-4 py-3" style="background: var(--home-surface-soft); border-color: var(--home-border);">
                                        <div>
                                            <p class="text-sm font-semibold">{{ $group }}</p>
                                            <p class="text-sm" style="color: var(--home-muted);">{{ $title }}</p>
                                        </div>
                                        <span class="text-xs font-semibold uppercase tracking-[0.18em]" style="color: var(--home-soft);">{{ $state }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="mt-10 grid gap-6 md:grid-cols-3">
                        <div class="rounded-3xl border p-6" style="background: var(--home-surface); border-color: var(--home-border);">
                            <p class="text-sm font-medium" style="color: var(--home-soft);">Customer side</p>
                            <h3 class="mt-3 text-xl font-semibold">Fast onboarding</h3>
                            <p class="mt-3 text-sm leading-6" style="color: var(--home-muted);">
                                Register, sign in and prepare the base flow before adding the full product catalog and cart features.
                            </p>
                        </div>

                        <div class="rounded-3xl border p-6" style="background: var(--home-surface); border-color: var(--home-border);">
                            <p class="text-sm font-medium" style="color: var(--home-soft);">Application flow</p>
                            <h3 class="mt-3 text-xl font-semibold">Session to order</h3>
                            <p class="mt-3 text-sm leading-6" style="color: var(--home-muted);">
                                The project is structured to move from public browsing to checkout, then on to staff processing and admin reporting.
                            </p>
                        </div>

                        <div class="rounded-3xl border p-6" style="background: var(--home-surface); border-color: var(--home-border);">
                            <p class="text-sm font-medium" style="color: var(--home-soft);">Project approach</p>
                            <h3 class="mt-3 text-xl font-semibold">Incremental delivery</h3>
                            <p class="mt-3 text-sm leading-6" style="color: var(--home-muted);">
                                Keep the UI clear, wire routes that exist now, and expose planned areas in the navigation without pretending they are already implemented.
                            </p>
                        </div>
                    </section>
                </main>
            </div>
        @endguest

        @auth
            <div class="min-h-screen" style="background: var(--home-bg); color: var(--home-text);">
                <livewire:layout.navigation />

                <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <section class="rounded-3xl border p-6 shadow-sm sm:p-8" style="background: var(--home-surface); border-color: var(--home-border); box-shadow: var(--home-shadow);">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p class="text-sm font-medium uppercase tracking-[0.18em]" style="color: var(--home-soft);">Workspace</p>
                                <h1 class="mt-3 text-3xl font-semibold">Welcome back, {{ Auth::user()->name }}.</h1>
                                <p class="mt-2 max-w-2xl text-sm leading-6" style="color: var(--home-muted);">
                                    This workspace shows the implemented authentication module and the planned storefront areas. Use the navigation bar to move between the pages that already exist.
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
                                <div class="rounded-2xl border px-4 py-3" style="background: var(--home-surface-soft); border-color: var(--home-border);">
                                    <p style="color: var(--home-soft);">Role</p>
                                    <p class="mt-1 font-semibold">{{ Auth::user()->user_type }}</p>
                                </div>
                                <div class="rounded-2xl border px-4 py-3" style="background: var(--home-surface-soft); border-color: var(--home-border);">
                                    <p style="color: var(--home-soft);">Authentication</p>
                                    <p class="mt-1 font-semibold">Ready</p>
                                </div>
                                <div class="rounded-2xl border px-4 py-3" style="background: var(--home-surface-soft); border-color: var(--home-border);">
                                    <p style="color: var(--home-soft);">Catalog</p>
                                    <p class="mt-1 font-semibold">Planned</p>
                                </div>
                                <div class="rounded-2xl border px-4 py-3" style="background: var(--home-surface-soft); border-color: var(--home-border);">
                                    <p style="color: var(--home-soft);">Orders</p>
                                    <p class="mt-1 font-semibold">Planned</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ([
                            ['G1 Authentication & Users', 'Profile, login, register, verification'],
                            ['G2 Catalog', 'index, show, filter'],
                            ['G3 Cart', 'index, create, update, remove, clear'],
                            ['G4 Orders', 'checkout, history, updates'],
                            ['G5 Private Images', 'index, upload, delete'],
                            ['G6 PDF & Email', 'receipts, notifications'],
                            ['G7 Preview', 'show'],
                            ['G8 Statistics', 'index'],
                        ] as [$title, $copy])
                            <div class="rounded-2xl border p-5" style="background: var(--home-surface); border-color: var(--home-border);">
                                <h2 class="text-base font-semibold">{{ $title }}</h2>
                                <p class="mt-3 text-sm leading-6" style="color: var(--home-muted);">{{ $copy }}</p>
                            </div>
                        @endforeach
                    </section>
                </main>
            </div>
        @endauth

        <script>
            (() => {
                const root = document.documentElement;
                const button = document.getElementById('theme-toggle-home');
                const label = document.querySelector('[data-home-theme-label]');

                const sync = () => {
                    if (label) {
                        label.textContent = root.dataset.theme === 'dark' ? 'Dark' : 'Light';
                    }
                };

                sync();

                button?.addEventListener('click', () => {
                    const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
                    window.funshirtApplyTheme(next);
                });

                document.addEventListener('funshirt-theme-changed', sync);
            })();
        </script>
    </body>
</html>
