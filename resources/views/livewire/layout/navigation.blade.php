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
    @php($cart = session('cart', []))
    @php($cartTotal = collect($cart)->sum('sub_total'))
    @php($cartCount = collect($cart)->sum('qty'))
    @php($showCart = ! $user || $user->isCustomer())
    @php($navClass = fn (): string => 'rounded-full px-3 py-2 text-sm font-medium transition')
    @php($navStyle = function (bool $active): string {
        return $active
            ? 'background: var(--app-surface-2); color: var(--app-text); border: 1px solid var(--app-border);'
            : 'color: var(--app-text); border: 1px solid transparent;';
    })

    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('catalog.index') }}" class="flex items-center gap-3" wire:navigate>
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-600 text-sm font-semibold text-white">FS</div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold">FunShirt</p>
                    <p class="text-xs" style="color: var(--app-muted);">Workspace</p>
                </div>
            </a>

            <div class="hidden items-center gap-2 md:flex">
                <a href="{{ route('catalog.index') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('catalog.index')) }}" wire:navigate>Catalog</a>
                @if ($user && $user->isCustomer())
                    <a href="{{ route('customize.create') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('customize.*')) }}" wire:navigate>Customize</a>
                @endif

                @if ($user?->isAdmin())
                    <a href="{{ route('admin.users.index') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('admin.users.*')) }}" wire:navigate>User Management</a>
                    <a href="{{ route('admin.categories.index') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('admin.categories.*')) }}" wire:navigate>Categories</a>
                    <a href="{{ route('admin.colors.index') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('admin.colors.*')) }}" wire:navigate>Colors</a>
                    <a href="{{ route('admin.tshirt-images.index') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('admin.tshirt-images.*')) }}" wire:navigate>Designs</a>
                    <a href="{{ route('admin.prices.index') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('admin.prices.*')) }}" wire:navigate>Prices</a>
                    <a href="{{ route('admin.statistics.index') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('admin.statistics.*')) }}" wire:navigate>Statistics</a>
                @endif
            </div>
        </div>

        <div class="hidden items-center gap-3 sm:flex">
            @if ($showCart)
                <a href="{{ route('cart.show') }}" class="{{ $navClass() }} inline-flex items-center gap-2" style="{{ $navStyle(request()->routeIs('cart.*')) }}" wire:navigate>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="20" r="1"></circle>
                        <circle cx="18" cy="20" r="1"></circle>
                        <path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.8a1 1 0 0 0 1-.8L21 7H7"></path>
                    </svg>
                    <span>Cart</span>
                    <span class="rounded-full bg-white/70 px-2 py-0.5 text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                        {{ $cartCount }} · &euro;{{ number_format($cartTotal, 2) }}
                    </span>
                </a>
            @endif

            <button
                type="button"
                id="theme-toggle-app"
                aria-label="Toggle theme"
                class="inline-flex items-center rounded-full border px-3 py-2 text-sm font-medium transition"
                style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
            >
                <span data-theme-label>Light</span>
            </button>

            @auth
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-sm font-medium transition" style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);">
                            @if($user->hasUploadedPhoto())
                                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-600 text-xs font-semibold leading-none text-white">
                                    <img
                                        src="{{ $user->photoFullUrl }}"
                                        alt=""
                                        class="block h-full w-full object-cover object-center"
                                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                    >
                                    <span class="hidden leading-none">{{ $user->initials() }}</span>
                                </span>
                            @else
                                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-xs font-semibold leading-none text-white">
                                    {{ $user->initials() }}
                                </span>
                            @endif

                            <div class="max-w-[10rem] truncate text-left" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            <svg class="h-4 w-4 shrink-0 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('orders.index')" wire:navigate>
                            Orders
                        </x-dropdown-link>

                        @if ($user->isStaff())
                            <x-dropdown-link :href="route('profile.password')" wire:navigate>
                                Change Password
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('profile.edit')" wire:navigate>
                                Profile
                            </x-dropdown-link>
                        @endif

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                Log Out
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('login')) }}" wire:navigate>Login</a>
                    <a href="{{ route('register') }}" class="{{ $navClass() }}" style="{{ $navStyle(request()->routeIs('register')) }}" wire:navigate>Register</a>
                </div>
            @endauth
        </div>

        <div class="flex items-center gap-2 sm:hidden">
            @if ($showCart)
                <a href="{{ route('cart.show') }}" class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-sm font-medium transition" style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);" wire:navigate>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="20" r="1"></circle>
                        <circle cx="18" cy="20" r="1"></circle>
                        <path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.8a1 1 0 0 0 1-.8L21 7H7"></path>
                    </svg>
                    <span>{{ $cartCount }}</span>
                </a>
            @endif

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
            <x-responsive-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.index')" wire:navigate>
                Catalog
            </x-responsive-nav-link>
            @if ($user && $user->isCustomer())
                <x-responsive-nav-link :href="route('customize.create')" :active="request()->routeIs('customize.*')" wire:navigate>
                    Customize
                </x-responsive-nav-link>
            @endif

            @if ($user?->isAdmin())
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" wire:navigate>
                    User Management
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" wire:navigate>
                    Categories
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.colors.index')" :active="request()->routeIs('admin.colors.*')" wire:navigate>
                    Colors
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.tshirt-images.index')" :active="request()->routeIs('admin.tshirt-images.*')" wire:navigate>
                    Designs
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.prices.index')" :active="request()->routeIs('admin.prices.*')" wire:navigate>
                    Prices
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.statistics.index')" :active="request()->routeIs('admin.statistics.*')" wire:navigate>
                    Statistics
                </x-responsive-nav-link>
            @endif

            @if ($showCart)
                <x-responsive-nav-link :href="route('cart.show')" :active="request()->routeIs('cart.*')" wire:navigate>
                    Cart
                </x-responsive-nav-link>
            @endif

            @guest
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')" wire:navigate>
                    Login
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')" wire:navigate>
                    Register
                </x-responsive-nav-link>
            @endguest
        </div>

        @auth
            <div class="border-t px-4 py-4" style="border-color: var(--app-border);">
                <div class="flex items-center gap-3">
                    @if($user->hasUploadedPhoto())
                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-600 text-sm font-semibold leading-none text-white">
                            <img
                                src="{{ $user->photoFullUrl }}"
                                alt=""
                                class="block h-full w-full object-cover object-center"
                                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                            >
                            <span class="hidden leading-none">{{ $user->initials() }}</span>
                        </span>
                    @else
                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-semibold leading-none text-white">
                            {{ $user->initials() }}
                        </span>
                    @endif

                    <div>
                        <div class="text-base font-medium" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                        <div class="text-sm" style="color: var(--app-muted);">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('orders.index') }}" class="block rounded-xl px-3 py-2 text-sm" style="background: var(--app-surface-2); color: var(--app-text);" wire:navigate>Orders</a>

                    @if ($user->isStaff())
                        <a href="{{ route('profile.password') }}" class="block rounded-xl px-3 py-2 text-sm" style="background: var(--app-surface-2); color: var(--app-text);" wire:navigate>Change Password</a>
                    @else
                        <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2 text-sm" style="background: var(--app-surface-2); color: var(--app-text);" wire:navigate>Profile</a>
                    @endif

                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            Log Out
                        </x-responsive-nav-link>
                    </button>
                </div>
            </div>
        @endauth
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
