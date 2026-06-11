<x-layouts::main-content title="Shopping Cart" heading="Your Cart" subheading="Review and adjust your selected items">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        @if (empty($cart))
            <div class="rounded-xl border border-zinc-200 bg-white px-6 py-12 text-center shadow-sm">
                <h3 class="text-lg font-semibold text-zinc-900">Your cart is empty</h3>
                <p class="mt-2 text-sm text-zinc-500">Add a design from the catalog to start an order.</p>
                <a href="{{ route('catalog.index') }}" class="mt-5 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Options</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-zinc-500">Unit Price</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-zinc-500">Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-zinc-500">Subtotal</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-zinc-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        @foreach ($cart as $key => $item)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ route('public.storage', ['path' => 'tshirt_images/' . $item['image_url']]) }}" alt="{{ $item['name'] }}" class="h-14 w-14 rounded-lg object-cover">
                                        <div>
                                            <div class="font-medium text-zinc-900">{{ $item['name'] }}</div>
                                            <div class="text-xs text-zinc-500">{{ ucfirst($item['type']) }} design</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('cart.update', $key) }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                        @csrf
                                        @method('PUT')
                                        <select name="color_code" class="rounded border border-zinc-300 bg-white px-2 py-1 text-sm" onchange="this.form.submit()">
                                            @foreach (\App\Models\Color::orderBy('name')->get() as $color)
                                                <option value="{{ $color->code }}" @selected($item['color_code'] === $color->code)>
                                                    {{ $color->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="size" class="rounded border border-zinc-300 bg-white px-2 py-1 text-sm" onchange="this.form.submit()">
                                            @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                                                <option value="{{ $size }}" @selected($item['size'] === $size)>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-zinc-700">&euro;{{ number_format($item['unit_price'], 2) }}</td>
                                <td class="px-4 py-4 text-right">
                                    <form method="POST" action="{{ route('cart.update', $key) }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="qty" value="{{ $item['qty'] }}" min="0" class="w-20 rounded border border-zinc-300 px-2 py-1 text-right text-sm">
                                        <button type="submit" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Update</button>
                                    </form>
                                </td>
                                <td class="px-4 py-4 text-right font-semibold text-zinc-900">&euro;{{ number_format($item['sub_total'], 2) }}</td>
                                <td class="px-4 py-4 text-right">
                                    <form method="POST" action="{{ route('cart.remove', $key) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex flex-col gap-4 border-t border-zinc-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-100">
                            Clear Cart
                        </button>
                    </form>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                        Continue Shopping
                    </a>
                </div>

                <div class="text-right">
                    <p class="text-xl font-bold text-zinc-900">Total: &euro;{{ number_format($total, 2) }}</p>
                    <a href="{{ route('checkout.index') }}" class="mt-2 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts::main-content>
