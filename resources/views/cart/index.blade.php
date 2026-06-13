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
                            @php($detailUrl = ($item['type'] ?? 'catalog') === 'catalog' ? route('catalog.show', $item['tshirt_image_id']) : null)
                            @php($canvasId = 'preview-' . md5($key))
                            @php($hasBasePreview = file_exists(public_path('storage/tshirt_base/' . $item['color_code'] . '.jpg')))
                            @php($designUrl = !empty($item['image_url']) ? route('public.storage', ['path' => 'tshirt_images/' . $item['image_url']]) : null)
                            @php($baseUrl = $hasBasePreview ? asset('storage/tshirt_base/' . $item['color_code'] . '.jpg') : null)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($baseUrl && $designUrl)
                                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100">
                                                <canvas
                                                    id="{{ $canvasId }}"
                                                    class="tshirt-preview block h-14 w-14"
                                                    data-base-url="{{ $baseUrl }}"
                                                    data-design-url="{{ $designUrl }}"
                                                    data-scale="0.6"
                                                    style="width:56px; height:56px;"
                                                ></canvas>
                                            </div>
                                        @elseif ($designUrl)
                                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100">
                                                <img src="{{ $designUrl }}" alt="{{ $item['name'] }}" class="h-full w-full object-contain">
                                            </div>
                                        @else
                                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg border border-dashed border-zinc-300 text-[10px] text-zinc-400">
                                                No image
                                            </div>
                                        @endif

                                        <div>
                                            @if ($detailUrl)
                                                <a href="{{ $detailUrl }}" wire:navigate class="font-medium text-zinc-900 hover:text-indigo-600">
                                                    {{ $item['name'] }}
                                                </a>
                                            @else
                                                <div class="font-medium text-zinc-900">{{ $item['name'] }}</div>
                                            @endif
                                            <div class="text-xs text-zinc-500">{{ ucfirst($item['type'] ?? 'catalog') }} design</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('cart.update', $key) }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                        @csrf
                                        @method('PUT')
                                        <select name="color_code" class="min-w-[8rem] rounded border border-zinc-300 bg-white px-2 py-1 text-sm" onchange="this.form.submit()">
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->code }}" @selected($item['color_code'] === $color->code)>{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="size" class="min-w-[6rem] rounded border border-zinc-300 bg-white px-2 py-1 text-sm" onchange="this.form.submit()">
                                            @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                                                <option value="{{ $size }}" @selected($item['size'] === $size)>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>

                                <td class="px-4 py-4 text-right text-sm">
                                    @if (!empty($item['qualifies_discount']))
                                        <div class="text-zinc-400 line-through">&euro;{{ number_format($item['original_unit_price'], 2) }}</div>
                                        <div class="font-semibold text-emerald-600">&euro;{{ number_format($item['unit_price'], 2) }}</div>
                                        <div class="text-xs text-zinc-500">-{{ rtrim(rtrim(number_format($item['discount_rate'], 2), '0'), '.') }}%</div>
                                    @else
                                        <div class="text-zinc-700">&euro;{{ number_format($item['unit_price'], 2) }}</div>
                                        @if (!empty($item['discount_threshold']))
                                            <div class="text-xs text-zinc-500">{{ $item['discount_threshold'] }}+ for discount</div>
                                        @endif
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-right">
                                    <form method="POST" action="{{ route('cart.update', $key) }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input
                                            type="number"
                                            name="qty"
                                            value="{{ $item['qty'] }}"
                                            min="0"
                                            class="w-20 rounded border border-zinc-300 px-2 py-1 text-right text-sm"
                                            onchange="this.form.submit()"
                                        >
                                    </form>
                                </td>

                                <td class="px-4 py-4 text-right">
                                    @if (!empty($item['qualifies_discount']))
                                        <div class="text-sm text-zinc-400 line-through">&euro;{{ number_format($item['original_sub_total'], 2) }}</div>
                                        <div class="font-semibold text-zinc-900">&euro;{{ number_format($item['sub_total'], 2) }}</div>
                                        <div class="text-xs text-emerald-600">Save &euro;{{ number_format($item['discount_amount'], 2) }}</div>
                                    @else
                                        <div class="font-semibold text-zinc-900">&euro;{{ number_format($item['sub_total'], 2) }}</div>
                                    @endif
                                </td>

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
                    @if (!empty($totalSavings) && $totalSavings > 0)
                        <p class="text-sm text-zinc-400 line-through">Original total: &euro;{{ number_format($originalTotal, 2) }}</p>
                        <p class="text-sm text-emerald-600">Discount saved: &euro;{{ number_format($totalSavings, 2) }}</p>
                    @endif
                    <p class="text-xl font-bold text-zinc-900">Total: &euro;{{ number_format($total, 2) }}</p>
                    @if ($checkoutHref)
                        <a href="{{ $checkoutHref }}" class="mt-2 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                            {{ $checkoutLabel }}
                        </a>
                    @else
                        <span class="mt-2 inline-flex cursor-not-allowed items-center justify-center rounded-lg bg-zinc-300 px-4 py-2 text-sm font-medium text-zinc-600">
                            {{ $checkoutLabel }}
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-layouts::main-content>
