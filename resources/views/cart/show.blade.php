<x-layouts::main-content title="My Cart" heading="Shopping Cart" subheading="Review your custom tailored merchandise selections">
    <div class="max-w-7xl mx-auto py-4">
        @if(!$cart || count($cart) === 0)
            <div class="p-12 text-center bg-zinc-50 border border-zinc-200 rounded-xl shadow-sm">
                <flux:icon.shopping-cart class="mx-auto size-16 text-zinc-400" />
                <h2 class="mt-4 text-2xl font-black text-zinc-700">Your basket is currently empty</h2>
                <p class="text-sm text-zinc-500 mt-2">Head back over to our designs catalogue to add custom items.</p>
                <div class="mt-6">
                    <flux:button variant="primary" href="{{ route('catalog.index') }}">Browse Catalog</flux:button>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart as $key => $item)
                        <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-xl shadow-sm flex items-center justify-between space-x-4">
                            
                            <div class="size-20 bg-zinc-200 rounded-lg flex items-center justify-center p-2 flex-shrink-0" style="background-color: #{{ $item['color_code'] }}">
                                @if(isset($item['image_url']) && $item['image_url'])
                                    <img src="{{ asset('storage/tshirt_images/' . $item['image_url']) }}" class="max-h-full max-w-full object-contain">
                                @else
                                    <flux:icon.photo class="size-8 text-zinc-400" />
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-base text-zinc-950 truncate">{{ $item['name'] }}</h4>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-zinc-500 mt-1">
                                    <span>Size: <strong class="text-zinc-700 font-bold">{{ $item['size'] }}</strong></span>
                                    <span>Color Code: <code class="font-mono bg-zinc-100 px-1 rounded">#{{ $item['color_code'] }}</code></span>
                                </div>
                                <div class="text-sm font-extrabold text-indigo-600 mt-2">
                                    €{{ number_format($item['unit_price'], 2) }} <span class="text-xs font-normal text-zinc-400">each</span>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <form action="{{ route('cart.update', $key) }}" method="POST" class="flex items-center space-x-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="100" class="w-14 text-center bg-white border border-zinc-300 rounded-md p-1 text-sm font-semibold focus:ring-2 focus:ring-indigo-500">
                                    <flux:button type="submit" size="sm" variant="ghost" square icon="check" tooltip="Update count" />
                                </form>

                                <form action="{{ route('cart.remove', $key) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <flux:button type="submit" size="sm" variant="danger" square icon="trash" tooltip="Remove item entry" />
                                </form>
                            </div>

                        </div>
                    @endforeach

                    <div class="flex justify-between items-center pt-2">
                        <flux:button href="{{ route('catalog.index') }}" variant="ghost" icon="arrow-left">Continue Shopping</flux:button>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Flush shopping cart context empty?');">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="danger" icon="x-mark">Clear Entire Cart</flux:button>
                        </form>
                    </div>
                </div>

                <div class="p-6 bg-zinc-50 border border-zinc-200 rounded-xl shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-zinc-950 pb-3 border-b border-zinc-200">Order Totals Overview</h3>
                    
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between text-zinc-600">
                            <span>Total Items Count:</span>
                            <span class="font-bold text-zinc-900">{{ array_sum(array_column($cart, 'qty')) }} units</span>
                        </div>
                        <div class="flex justify-between text-zinc-600">
                            <span>Subtotal Accumulation:</span>
                            <span class="font-semibold text-zinc-900">€{{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        @if($discount > 0)
                            <div class="flex justify-between text-emerald-600 font-medium">
                                <span>Bulk Volume Discount Allocation:</span>
                                <span>-€{{ number_format($discount, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-zinc-200 flex justify-between items-baseline">
                        <span class="text-base font-bold text-zinc-900">Grand Total Due:</span>
                        <span class="text-3xl font-black text-indigo-600">
                            €{{ number_format($total, 2) }}
                        </span>
                    </div>

                    <div class="pt-2">
                        <flux:button href="{{ route('checkout.index') }}" variant="primary" class="w-full justify-center py-2.5 font-bold text-base" icon="credit-card">
                            Proceed to Order Checkout
                        </flux:button>
                    </div>
                </div>

            </div>
        @endif
    </div>
</x-layouts::main-content>