<x-layouts::main-content title="Customize" heading="Customize Your T-Shirt" subheading="Upload a design, choose the shirt color, size and quantity">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 rounded-xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm lg:grid-cols-[minmax(0,1fr)_420px]">
            <div id="custom-preview-panel" class="relative flex min-h-[360px] items-center justify-center rounded-xl p-8 shadow-inner" style="background-color: #e4e4e7;">
                <img
                    id="custom-design-preview"
                    alt="Custom design preview"
                    class="hidden max-h-[300px] max-w-[300px] object-contain"
                >
                <div id="custom-preview-placeholder" class="text-center text-sm text-zinc-500">
                    Upload an image to preview your custom T-shirt.
                </div>
            </div>

            <div>
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-zinc-900">Create a custom shirt</h2>
                    <p class="mt-4 text-sm leading-6 text-zinc-600">
                        Guests can upload a design and add a customized T-shirt directly to the shopping cart.
                    </p>
                </div>

                <form action="{{ route('customize.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="custom_name" class="mb-2 block text-sm font-medium text-zinc-700">Design Name</label>
                        <input
                            id="custom_name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="My custom shirt"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>

                    <div>
                        <label for="design_image" class="mb-2 block text-sm font-medium text-zinc-700">Upload Design</label>
                        <input
                            id="design_image"
                            type="file"
                            name="design_image"
                            accept="image/png,image/jpeg,image/webp"
                            required
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700"
                        >
                    </div>

                    <div>
                        <label for="custom_color_code" class="mb-2 block text-sm font-medium text-zinc-700">Shirt Color</label>
                        <select name="color_code" id="custom_color_code" required class="w-full rounded-lg border border-zinc-300 bg-white p-2.5 text-sm shadow-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach ($colors as $color)
                                <option value="{{ $color->code }}" data-hex="{{ $color->code }}" @selected(old('color_code') === $color->code)>
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700">Size</label>
                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                            @foreach ($sizes as $size)
                                <label class="cursor-pointer rounded-lg border border-zinc-300 bg-white px-3 py-2 text-center text-sm font-medium transition hover:border-indigo-500 hover:text-indigo-600">
                                    <input type="radio" name="size" value="{{ $size }}" required class="sr-only peer" @checked(old('size', 'M') === $size)>
                                    <span class="peer-checked:text-indigo-600">{{ $size }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="max-w-[180px]">
                        <label for="custom_qty" class="mb-2 block text-sm font-medium text-zinc-700">Quantity</label>
                        <input
                            id="custom_qty"
                            type="number"
                            name="qty"
                            min="1"
                            max="100"
                            value="{{ old('qty', 1) }}"
                            required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>

                    <div class="space-y-4 rounded-lg bg-zinc-100 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-zinc-600">Unit price</span>
                            <span id="custom-unit-price" class="text-lg font-bold text-zinc-900">
                                &euro;{{ number_format($customPrice, 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-zinc-600">Total</span>
                            <span id="custom-total-price" class="text-2xl font-bold text-indigo-600">
                                &euro;{{ number_format($customPrice, 2) }}
                            </span>
                        </div>

                        <div class="rounded-lg border border-dashed border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-600">
                            <p class="font-medium text-zinc-700">Discount rule</p>
                            <p class="mt-1">
                                Buy
                                <span class="font-semibold text-zinc-900">{{ $discountThreshold ?? '-' }}</span>
                                or more and the unit price changes to
                                <span class="font-semibold text-emerald-600">&euro;{{ number_format($customDiscountPrice, 2) }}</span>.
                            </p>
                            <p id="custom-discount-summary" class="mt-2 text-xs text-zinc-500">
                                No discount applied.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-medium text-white transition hover:bg-indigo-500">
                            Add Custom T-Shirt to Cart
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
            const initCustomPreview = () => {
                const fileInput = document.getElementById('design_image');
                const colorSelect = document.getElementById('custom_color_code');
                const panel = document.getElementById('custom-preview-panel');
                const previewImage = document.getElementById('custom-design-preview');
                const placeholder = document.getElementById('custom-preview-placeholder');
                const qtyInput = document.getElementById('custom_qty');
                const unitPrice = document.getElementById('custom-unit-price');
                const totalPrice = document.getElementById('custom-total-price');
                const discountSummary = document.getElementById('custom-discount-summary');

                if (!fileInput || !colorSelect || !panel || !previewImage || !placeholder || !qtyInput || !unitPrice || !totalPrice || !discountSummary) {
                    return;
                }

                const basePrice = {{ json_encode((float) $customPrice) }};
                const discountPrice = {{ json_encode((float) $customDiscountPrice) }};
                const discountThreshold = {{ json_encode($discountThreshold) }};

                const formatEuro = (value) => new Intl.NumberFormat('en-IE', {
                    style: 'currency',
                    currency: 'EUR',
                }).format(value);

                const updatePanelColor = () => {
                    const selectedOption = colorSelect.options[colorSelect.selectedIndex];
                    const code = selectedOption?.getAttribute('data-hex') || 'e4e4e7';
                    panel.style.backgroundColor = `#${String(code).replace('#', '')}`;
                };

                const updatePreviewImage = () => {
                    const file = fileInput.files?.[0];

                    if (!file) {
                        previewImage.classList.add('hidden');
                        previewImage.removeAttribute('src');
                        placeholder.classList.remove('hidden');
                        return;
                    }

                    const objectUrl = URL.createObjectURL(file);
                    previewImage.src = objectUrl;
                    previewImage.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };

                const updatePricing = () => {
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
                };

                if (!fileInput.dataset.previewBound) {
                    fileInput.addEventListener('change', updatePreviewImage);
                    fileInput.dataset.previewBound = 'true';
                }

                if (!colorSelect.dataset.colorBound) {
                    colorSelect.addEventListener('change', updatePanelColor);
                    colorSelect.dataset.colorBound = 'true';
                }

                if (!qtyInput.dataset.qtyBound) {
                    qtyInput.addEventListener('input', updatePricing);
                    qtyInput.dataset.qtyBound = 'true';
                }

                updatePanelColor();
                updatePreviewImage();
                updatePricing();
            };

            document.addEventListener('DOMContentLoaded', initCustomPreview);
            document.addEventListener('livewire:navigated', initCustomPreview);
            initCustomPreview();
        })();
    </script>
</x-layouts::main-content>
