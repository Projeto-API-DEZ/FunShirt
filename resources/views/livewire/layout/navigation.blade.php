<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="border-b backdrop-blur" style="background: var(--app-nav); border-color: var(--app-border); color: var(--app-text);">
    @php($user = auth()->user())
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <a href="/" class="flex items-center gap-3" wire:navigate>
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-600 text-sm font-semibold text-white">FS</div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold">FunShirt</p>
                    <p class="text-xs" style="color: var(--app-muted);">Workspace</p>
                </div>
            </a>

            <div class="hidden items-center gap-2 md:flex">
                <a href="/" class="rounded-full px-3 py-2 text-sm font-medium transition hover:bg-black/5" wire:navigate>Home</a>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </x-nav-link>
                <a href="{{ route('profile') }}" class="rounded-full px-3 py-2 text-sm font-medium transition hover:bg-black/5" wire:navigate>Profile</a>
                <span class="rounded-full px-3 py-2 text-sm font-medium opacity-70" style="background: var(--app-surface-2); color: var(--app-muted);">Catalog</span>
                <span class="rounded-full px-3 py-2 text-sm font-medium opacity-70" style="background: var(--app-surface-2); color: var(--app-muted);">Cart</span>
                <span class="rounded-full px-3 py-2 text-sm font-medium opacity-70" style="background: var(--app-surface-2); color: var(--app-muted);">Orders</span>
            </div>
        </div>

        <div class="hidden items-center gap-3 sm:flex">
            <button
                type="button"
                id="theme-toggle-app"
                aria-label="Toggle theme"
                class="inline-flex items-center rounded-full border px-3 py-2 text-sm font-medium transition"
                style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
            >
                <span data-theme-label>Light</span>
            </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                    <button class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-sm font-medium transition" style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);">
                        @if($user->hasUploadedPhoto())
                            <span
                                class="inline-flex shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-600 text-xs font-semibold leading-none text-white"
                                style="width: 2rem; height: 2rem; min-width: 2rem; min-height: 2rem;"
                            >
                                <img
                                    src="{{ $user->photoFullUrl }}"
                                    alt=""
                                    class="block"
                                    style="width: 100%; height: 100%; object-fit: cover; object-position: center;"
                                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                >
                                <span class="hidden leading-none">{{ $user->initials() }}</span>
                            </span>
                        @else
                            <span
                                class="inline-flex shrink-0 items-center justify-center rounded-full bg-indigo-600 text-xs font-semibold leading-none text-white"
                                style="width: 2rem; height: 2rem; min-width: 2rem; min-height: 2rem;"
                            >
                                {{ $user->initials() }}
                            </span>
                        @endif
                        <div class="max-w-[11rem] truncate text-left" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                        <div class="shrink-0">
                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <span class="block px-4 py-2 text-sm" style="color: var(--app-muted);">
                        {{ __('Orders') }}
                    </span>

                    <button wire:click="logout" class="w-full text-start">
                        <x-dropdown-link>
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </button>
                </x-slot>
            </x-dropdown>
        </div>

        <div class="flex items-center gap-2 sm:hidden">
            <button
                type="button"
                id="theme-toggle-app-mobile"
                aria-label="Toggle theme"
                class="inline-flex items-center rounded-full border px-3 py-2 text-sm font-medium transition"
                style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
            >
                <span data-theme-label-mobile>Light</span>
            </button>

            <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md border p-2 transition" style="border-color: var(--app-border); background: var(--app-surface); color: var(--app-text);">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t sm:hidden" style="border-color: var(--app-border);">
        <div class="space-y-1 px-4 py-3">
            <x-responsive-nav-link href="/" wire:navigate>
                Home
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile')" wire:navigate>
                {{ __('Profile') }}
            </x-responsive-nav-link>
            <div class="rounded-xl px-3 py-2 text-sm" style="background: var(--app-surface-2); color: var(--app-muted);">Catalog</div>
            <div class="rounded-xl px-3 py-2 text-sm" style="background: var(--app-surface-2); color: var(--app-muted);">Cart</div>
            <div class="rounded-xl px-3 py-2 text-sm" style="background: var(--app-surface-2); color: var(--app-muted);">Orders</div>
        </div>

        <div class="border-t px-4 py-4" style="border-color: var(--app-border);">
            <div class="flex items-center gap-3">
                @if($user->hasUploadedPhoto())
                    <span
                        class="inline-flex shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-600 text-sm font-semibold leading-none text-white"
                        style="width: 2.5rem; height: 2.5rem; min-width: 2.5rem; min-height: 2.5rem;"
                    >
                        <img
                            src="{{ $user->photoFullUrl }}"
                            alt=""
                            class="block"
                            style="width: 100%; height: 100%; object-fit: cover; object-position: center;"
                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                        >
                        <span class="hidden leading-none">{{ $user->initials() }}</span>
                    </span>
                @else
                    <span
                        class="inline-flex shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-semibold leading-none text-white"
                        style="width: 2.5rem; height: 2.5rem; min-width: 2.5rem; min-height: 2.5rem;"
                    >
                        {{ $user->initials() }}
                    </span>
                @endif
                <div>
                    <div class="text-base font-medium" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="text-sm" style="color: var(--app-muted);">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <div class="rounded-xl px-3 py-2 text-sm" style="background: var(--app-surface-2); color: var(--app-muted);">Orders</div>
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
    (() => {
        const root = document.documentElement;
        const desktop = document.getElementById('theme-toggle-app');
        const mobile = document.getElementById('theme-toggle-app-mobile');
        const desktopLabel = document.querySelector('[data-theme-label]');
        const mobileLabel = document.querySelector('[data-theme-label-mobile]');

        const syncLabel = () => {
            const label = root.dataset.theme === 'dark' ? 'Dark' : 'Light';
            if (desktopLabel) desktopLabel.textContent = label;
            if (mobileLabel) mobileLabel.textContent = label;
        };

        const toggleTheme = () => {
            const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
            window.funshirtApplyTheme(next);
        };

        syncLabel();
        document.addEventListener('funshirt-theme-changed', syncLabel);
        desktop?.addEventListener('click', toggleTheme);
        mobile?.addEventListener('click', toggleTheme);
    })();
</script>
