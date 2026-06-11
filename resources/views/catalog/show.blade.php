<x-layouts::main-content title="Configure T-Shirt" heading="Customize Design" subheading="Select your color, sizing options, and quantities">
    <div class="max-w-5xl mx-auto py-4">
        <div class="grid grid-cols-1 gap-8 rounded-xl border border-zinc-200 bg-zinc-50 p-8 shadow-sm md:grid-cols-2">
            <div id="preview-panel" class="relative flex min-h-[350px] items-center justify-center rounded-xl bg-zinc-100 p-8 shadow-inner">
                @if ($image->image_url)
                    <img src="{{ route('public.storage', ['path' => 'tshirt_images/' . $image->image_url]) }}" alt="{{ $image->name }}" class="z-10 max-h-[280px] max-w-[280px] object-contain">
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl border border-dashed border-zinc-300 text-sm font-medium text-zinc-400">
                        No image
                    </div>
                @endif
            </div>

            <div>
                <div class="mb-6">
                    <h2 class="text-2xl font-black tracking-tight text-zinc-950">{{ $image->name }}</h2>
                    <p class="mt-1 text-sm italic text-zinc-500">Category: {{ $image->category?->name ?? 'General' }}</p>
                    <p class="mt-3 text-sm font-light leading-relaxed text-zinc-600">
                        {{ $image->description }}
                    </p>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tshirt_image_id" value="{{ $image->id }}">

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-800">Base Shirt Fabric Color</label>
                        <select name="color_code" id="color_code_selector" required class="w-full rounded-lg border border-zinc-300 bg-white p-2.5 text-sm font-medium shadow-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach ($colors as $color)
                                <option value="{{ $color->code }}" data-hex="{{ $color->code }}">
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-zinc-800">Size Dimensions</label>
                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-6">
                            @foreach ($sizes as $size)
                                <label class="block cursor-pointer rounded-lg border border-zinc-300 p-2 text-center text-sm font-bold transition hover:bg-zinc-100">
                                    <input type="radio" name="size" value="{{ $size }}" required class="peer sr-only">
                                    <span class="peer-checked:text-indigo-600">{{ $size }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="w-full sm:w-1/3">
                        <label for="qty" class="mb-1.5 block text-sm font-semibold text-zinc-800">Quantity Ordered</label>
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

                    <div class="flex items-center justify-between rounded-lg bg-zinc-100 p-4">
                        <span class="text-sm font-medium text-zinc-600">Unit Estimate Price:</span>
                        <span class="text-2xl font-black text-indigo-600">
                            €{{ number_format($catalogPrice, 2) }}
                        </span>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-base font-bold text-white transition hover:bg-indigo-500">
                            Add Customized Combination to Cart
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selector = document.getElementById('color_code_selector');
            const panel = document.getElementById('preview-panel');

            function updatePreviewColor() {
                const selectedOption = selector.options[selector.selectedIndex];

                if (selectedOption) {
                    const code = selectedOption.getAttribute('data-hex') || 'f4f4f5';
                    panel.style.backgroundColor = `#${code.replace('#', '')}`;
                }
            }

            selector.addEventListener('change', updatePreviewColor);
            updatePreviewColor();
        });
    </script>
</x-layouts::main-content>
