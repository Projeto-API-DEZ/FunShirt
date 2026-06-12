@php($initialColorCode = '#' . ltrim((string) ($colors->first()?->code ?? 'e4e4e7'), '#'))

<x-layouts::main-content title="Configure T-Shirt" heading="Customize Design" subheading="Select your color, size and quantity">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 rounded-xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm lg:grid-cols-[minmax(0,1fr)_420px]">
            <div id="preview-panel" class="relative flex min-h-[360px] items-center justify-center rounded-xl p-8 shadow-inner" style="background-color: {{ $initialColorCode }};">
                @if ($image->image_url)
                    <img
                        src="{{ route('public.storage', ['path' => 'tshirt_images/' . $image->image_url]) }}"
                        alt="{{ $image->name }}"
                        class="z-10 max-h-[300px] max-w-[300px] object-contain"
                    >
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl border border-dashed border-zinc-300 text-sm font-medium text-zinc-400">
                        No image
                    </div>
                @endif
            </div>

            <div>
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-zinc-900">{{ $image->name }}</h2>
                    <p class="mt-1 text-sm text-zinc-500">{{ $image->category?->name ?? 'General' }}</p>
                    <p class="mt-4 text-sm leading-6 text-zinc-600">
                        {{ $image->description ?: 'No description available for this design.' }}
                    </p>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tshirt_image_id" value="{{ $image->id }}">

                    <div>
                        <label for="color_code_selector" class="mb-2 block text-sm font-medium text-zinc-700">Color</label>
                        <select name="color_code" id="color_code_selector" required class="w-full rounded-lg border border-zinc-300 bg-white p-2.5 text-sm shadow-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach ($colors as $color)
                                <option value="{{ $color->code }}" data-hex="{{ $color->code }}">
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700">Size</label>
                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-6">
                            @foreach ($sizes as $size)
                                <label class="cursor-pointer rounded-lg border border-zinc-300 bg-white px-3 py-2 text-center text-sm font-medium transition hover:border-indigo-500 hover:text-indigo-600">
                                    <input type="radio" name="size" value="{{ $size }}" required class="sr-only peer">
                                    <span class="peer-checked:text-indigo-600">{{ $size }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="max-w-[180px]">
                        <label for="qty" class="mb-2 block text-sm font-medium text-zinc-700">Quantity</label>
                        <input
                            id="qty"
                            type="number"
                            name="qty"
                            min="1"
                            max="100"
                            value="1"
                            required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>

                    <div class="space-y-4 rounded-lg bg-zinc-100 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-zinc-600">Unit price</span>
                            <span id="unit-price" class="text-lg font-bold text-zinc-900">
                                &euro;{{ number_format($catalogPrice, 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-zinc-600">Total</span>
                            <span id="total-price" class="text-2xl font-bold text-indigo-600">
                                &euro;{{ number_format($catalogPrice, 2) }}
                            </span>
                        </div>

                        <div class="rounded-lg border border-dashed border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-600">
                            <p class="font-medium text-zinc-700">Discount rule</p>
                            <p class="mt-1">
                                Buy
                                <span class="font-semibold text-zinc-900">{{ $discountThreshold ?? '-' }}</span>
                                or more and the unit price changes to
                                <span class="font-semibold text-emerald-600">&euro;{{ number_format($catalogDiscountPrice, 2) }}</span>.
                            </p>
                            <p id="discount-summary" class="mt-2 text-xs text-zinc-500">
                                No discount applied.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-medium text-white transition hover:bg-indigo-500">
                            Add to Cart
                        </button>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-3 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                            Back to Catalog
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const initCatalogPreview = () => {
            const selector = document.getElementById('color_code_selector');
            const panel = document.getElementById('preview-panel');
            const qtyInput = document.getElementById('qty');
            const unitPrice = document.getElementById('unit-price');
            const totalPrice = document.getElementById('total-price');
            const discountSummary = document.getElementById('discount-summary');

            if (!selector || !panel || !qtyInput || !unitPrice || !totalPrice || !discountSummary) {
                return;
            }

            const basePrice = {{ json_encode((float) $catalogPrice) }};
            const discountPrice = {{ json_encode((float) $catalogDiscountPrice) }};
            const discountThreshold = {{ json_encode($discountThreshold) }};

            function formatEuro(value) {
                return new Intl.NumberFormat('en-IE', {
                    style: 'currency',
                    currency: 'EUR',
                }).format(value);
            }

            function updatePreviewColor() {
                const selectedOption = selector.options[selector.selectedIndex];

                if (!selectedOption) {
                    return;
                }

                const code = selectedOption.getAttribute('data-hex') || 'e4e4e7';
                panel.style.backgroundColor = `#${code.replace('#', '')}`;
            }

            function updatePricing() {
                const qty = Math.max(parseInt(qtyInput.value || '1', 10), 1);
                const hasDiscount = discountThreshold && qty >= discountThreshold;
                const currentUnitPrice = hasDiscount ? discountPrice : basePrice;
                const originalTotal = qty * basePrice;
                const discountedTotal = qty * currentUnitPrice;
                const savings = originalTotal - discountedTotal;

                unitPrice.textContent = formatEuro(currentUnitPrice);
                totalPrice.textContent = formatEuro(discountedTotal);

                if (hasDiscount) {
                    discountSummary.textContent = `Discount active: save ${formatEuro(savings)} on ${qty} items.`;
                } else if (discountThreshold) {
                    discountSummary.textContent = `Add ${Math.max(discountThreshold - qty, 0)} more item(s) to unlock the ${formatEuro(discountPrice)} unit price.`;
                } else {
                    discountSummary.textContent = 'No discount applied.';
                }
            }

            if (!selector.dataset.previewBound) {
                selector.addEventListener('change', updatePreviewColor);
                selector.dataset.previewBound = 'true';
            }

            if (!qtyInput.dataset.pricingBound) {
                qtyInput.addEventListener('input', updatePricing);
                qtyInput.dataset.pricingBound = 'true';
            }

            updatePreviewColor();
            updatePricing();
            };

            document.addEventListener('DOMContentLoaded', initCatalogPreview);
            document.addEventListener('livewire:navigated', initCatalogPreview);
            initCatalogPreview();
        })();
    </script>
</x-layouts::main-content>
