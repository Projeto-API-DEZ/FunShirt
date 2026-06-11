<x-layouts::main-content title="Shopping Cart" heading="Your Cart" subheading="Review and adjust your items">
    <div class="max-w-6xl mx-auto py-6">
        @if(empty($cart))
            <div class="text-center py-12 bg-zinc-50 rounded-xl">
                <flux:icon.shopping-cart class="mx-auto size-12 text-zinc-400" />
                <h3 class="mt-2 text-lg font-medium">Your cart is empty</h3>
                <p class="text-sm text-zinc-500">Start adding some cool t‑shirts!</p>
                <flux:button href="{{ route('catalog.index') }}" class="mt-4">Continue Shopping</flux:button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500">Color / Size</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500">Unit Price</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500">Quantity</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500">Subtotal</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 bg-white">
                        @foreach($cart as $key => $item)
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/tshirt_images/'.$item['image_url']) }}" class="h-14 w-14 object-cover rounded">
                                    <div>
                                        <div class="font-medium">{{ $item['name'] }}</div>
                                        <div class="text-xs text-zinc-500">{{ ucfirst($item['type']) }} design</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <form method="POST" action="{{ route('cart.update', $key) }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    @csrf @method('PUT')
                                    <select name="color_code" class="border rounded px-2 py-1 text-sm" onchange="this.form.submit()">
                                        @foreach(\App\Models\Color::orderBy('name')->get() as $color)
                                            <option value="{{ $color->code }}" {{ $item['color_code'] == $color->code ? 'selected' : '' }}>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="size" class="border rounded px-2 py-1 text-sm" onchange="this.form.submit()">
                                        @foreach(['XS','S','M','L','XL'] as $size)
                                            <option value="{{ $size }}" {{ $item['size'] == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-4 py-4 text-right">€{{ number_format($item['unit_price'], 2) }}</td>
                            <td class="px-4 py-4 text-right">
                                <form method="POST" action="{{ route('cart.update', $key) }}" class="inline-flex items-center gap-1">
                                    @csrf @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" class="w-20 border rounded px-2 py-1 text-right">
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm">Update</button>
                                </form>
                            </td>
                            <td class="px-4 py-4 text-right font-semibold">€{{ number_format($item['subtotal'], 2) }}</td>
                            <td class="px-4 py-4 text-right">
                                <form method="POST" action="{{ route('cart.remove', $key) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-between items-center border-t pt-6 gap-4">
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf @method('DELETE')
                        <flux:button type="submit" variant="danger">Clear Cart</flux:button>
                    </form>
                    <flux:button href="{{ route('catalog.index') }}" variant="outline">Continue Shopping</flux:button>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold">Total: €{{ number_format($total, 2) }}</p>
                    <flux:button href="{{ route('cart.checkout') }}" variant="primary" class="mt-2">Proceed to Checkout</flux:button>
                </div>
            </div>
        @endif
    </div>
</x-layouts::main-content>