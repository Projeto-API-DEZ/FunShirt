<x-layouts::main-content title="My Custom Images" heading="My Custom Image Library" subheading="Manage your private designs and reuse them in future orders">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-semibold text-zinc-900">Private Designs</h2>
                <p class="mt-1 text-sm text-zinc-500">Only you can view these uploaded images. Reuse them directly from this library.</p>
            </div>

            <a href="{{ route('customize.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500">
                Upload New Design
            </a>
        </div>

        @if ($images->isEmpty())
            <div class="rounded-xl border border-zinc-200 bg-white px-6 py-12 text-center shadow-sm">
                <h3 class="text-lg font-semibold text-zinc-900">No custom images yet</h3>
                <p class="mt-2 text-sm text-zinc-500">Upload your first private design to start building your personal library.</p>
                <a href="{{ route('customize.create') }}" class="mt-5 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                    Create First Design
                </a>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($images as $image)
                    <article class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                        <div
                            class="flex aspect-square items-center justify-center border-b border-zinc-200 p-5 transition-colors"
                            data-preview-panel
                            style="background-color: var(--app-image-bg);"
                        >
                            <img
                                src="{{ route('customize.image', $image) }}"
                                alt="{{ $image->name }}"
                                class="max-h-full max-w-full object-contain"
                            >
                        </div>

                        <div class="space-y-5 p-5">
                            <div>
                                <h3 class="truncate text-lg font-semibold text-zinc-900">{{ $image->name }}</h3>
                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-zinc-600">
                                    {{ $image->description ?: 'Private reusable design.' }}
                                </p>
                            </div>

                            <form method="POST" action="{{ route('customize.library.add', $image) }}" class="space-y-4">
                                @csrf

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-zinc-500">Color</label>
                                        <select
                                            name="color_code"
                                            data-preview-color
                                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        >
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->code }}" data-color-code="{{ $color->code }}">{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-zinc-500">Size</label>
                                        <select name="size" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-[120px_minmax(0,1fr)] sm:items-end">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-zinc-500">Qty</label>
                                        <input
                                            type="number"
                                            name="qty"
                                            min="1"
                                            max="100"
                                            value="1"
                                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        >
                                    </div>

                                    <div class="rounded-lg border border-dashed border-zinc-300 bg-zinc-50 px-3 py-2 text-xs text-zinc-600">
                                        Reuse price: <span class="font-semibold text-zinc-900">&euro;{{ number_format($customPrice, 2) }}</span>
                                        @if ($discountThreshold)
                                            <span class="block mt-1">Discount from {{ $discountThreshold }} units: &euro;{{ number_format($customDiscountPrice, 2) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500">
                                        Add to Cart Again
                                    </button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('customize.library.destroy', $image) }}" onsubmit="return confirm('Remove this custom image from your library?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition hover:bg-rose-100">
                                    Delete From Library
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $images->links() }}
            </div>
        @endif
    </div>

    <script>
        document.querySelectorAll('[data-preview-color]').forEach((select) => {
            const card = select.closest('article');
            const previewPanel = card?.querySelector('[data-preview-panel]');

            if (!previewPanel) {
                return;
            }

            const normalizeColor = (value) => {
                if (!value) {
                    return 'var(--app-image-bg)';
                }

                const color = value.trim();

                if (color.startsWith('#') || color.startsWith('rgb') || color.startsWith('hsl')) {
                    return color;
                }

                if (/^[0-9a-fA-F]{6}([0-9a-fA-F]{2})?$/.test(color)) {
                    return `#${color}`;
                }

                return color;
            };

            const updatePreviewBackground = () => {
                const selectedOption = select.options[select.selectedIndex];
                const selectedColor = selectedOption?.dataset.colorCode ?? select.value;
                previewPanel.style.backgroundColor = normalizeColor(selectedColor);
            };

            updatePreviewBackground();
            select.addEventListener('change', updatePreviewBackground);
        });
    </script>
</x-layouts::main-content>
