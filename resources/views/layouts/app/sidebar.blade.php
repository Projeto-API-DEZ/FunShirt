<nav class="w-full lg:w-64 bg-zinc-100 border-b lg:border-b-0 lg:border-r border-zinc-200 p-4 flex flex-col justify-between">
    <div class="space-y-6">
        <!-- App Title Head -->
        <div class="flex items-center space-x-2 px-2">
            <span class="text-xl font-black tracking-wider text-indigo-600 uppercase">FunShirt</span>
        </div>

        <!-- Links Navigation Navigation Links Section -->
        <div class="space-y-1">
            <flux:navlist>
                <flux:navlist.item icon="home" href="{{ route('catalog.index') }}">Public Catalog</flux:navlist.item>
                <flux:navlist.item icon="shopping-cart" href="{{ route('cart.show') }}">
                    Shopping Cart 
                    @if(session()->has('cart') && count(session('cart')) > 0)
                        <span class="ms-2 bg-indigo-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">
                            {{ array_sum(array_column(session('cart'), 'qty')) }}
                        </span>
                    @endif
                </flux:navlist.item>

                @auth
                    <flux:navlist.item icon="clock" href="{{ route('orders.index') }}">Track Orders</flux:navlist.item>
                    <flux:navlist.item icon="photo" href="{{ route('tshirt-images.index') }}">
                        {{ auth()->user()->isCustomer() ? 'My Custom Designs' : 'Catalog Portfolio' }}
                    </flux:navlist.item>

                    @if(auth()->user()->isAdmin())
                        <flux:navlist.item icon="currency-euro" href="{{ route('prices.index') }}">Global Pricing Matrix</flux:navlist.item>
                        <flux:navlist.item icon="users" href="{{ route('users.index') }}">User Operations Admin</flux:navlist.item>
                    @endif
                @endauth
            </flux:navlist>
        </div>
    </div>

    <!-- Identity Operational Context Footer Segment -->
    <div class="mt-auto pt-4 border-t border-zinc-200">
        @auth
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center space-x-3">
                    <div class="h-9 w-9 bg-indigo-600 text-white flex items-center justify-center font-bold text-sm rounded-full shadow-inner">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div>
                        <a href="{{ route('profile.edit') }}" class="block text-sm font-semibold hover:underline text-zinc-800 truncate max-w-[120px]">
                            {{ auth()->user()->name }}
                        </a>
                        <span class="block text-xs text-zinc-500">
                            {{ auth()->user()->isAdmin() ? 'Administrator' : (auth()->user()->isStaff() ? 'Staff' : 'Customer') }}
                        </span>
                    </div>
                </div>
                
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <flux:button size="sm" square variant="ghost" type="submit" icon="arrow-right-start-on-rectangle" tooltip="Logout Profile" />
                </form>
            </div>
        @else
            <div class="grid grid-cols-2 gap-2 px-2">
                <flux:button variant="ghost" size="sm" href="{{ route('login') }}">Sign In</flux:button>
                <flux:button variant="primary" size="sm" href="{{ route('register') }}">Register</flux:button>
            </div>
        @endauth
    </div>
</nav>