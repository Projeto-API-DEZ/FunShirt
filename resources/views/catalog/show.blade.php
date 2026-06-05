<x-layouts::main-content title="Configure T-Shirt" heading="Customize Design" subheading="Select your color, sizing options, and quantities">
    <div class="max-w-5xl mx-auto py-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8 bg-zinc-50 border border-zinc-200 rounded-xl shadow-sm">
            
            <div class="bg-zinc-100 rounded-xl p-8 flex items-center justify-center relative min-h-[350px] shadow-inner" id="preview-panel">
                @if($image->image_url)
                    <img src="{{ asset('storage/tshirt_images/' . $image->image_url) }}" alt="{{ $image->name }}" class="max-h-[280px] max-w-[280px] object-contain z-10">
                @else
                    <flux:icon.photo class="size-20 text-zinc-400" />
                @endif
            </div>

            <div>
                <div class="mb-6">
                    <h2 class="text-2xl font-black tracking-tight text-zinc-950">{{ $image->name }}</h2>
                    <p class="text-sm text-zinc-500 mt-1 italic">Category: {{ $image->category?->name ?? 'General' }}</p>
                    <p class="text-sm font-light text-zinc-600 mt-3 leading-relaxed">
                        {{ $image->description }}
                    </p>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tshirt_image_id" value="{{ $image->id }}">

                    <div>
                        <label class="block text-sm font-semibold mb-1.5 text-zinc-800">Base Shirt Fabric Color</label>
                        <select name="color_code" id="color_code_selector" required class="w-full bg-white border border-zinc-300 rounded-lg p-2.5 text-sm font-medium shadow-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach($colors as $color)
                                <option value="{{ $color->code }}" data-hex="#{{ $color->code }}">
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1.5 text-zinc-800">Size Dimensions</label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($sizes as $size)
                                <label class="border border-zinc-300 rounded-lg p-2 text-center text-sm font-bold cursor-pointer hover:bg-zinc-100 transition block">
                                    <input type="radio" name="size" value="{{ $size }}" required class="sr-only peer">
                                    <span class="peer-checked:text-indigo-600">{{ $size }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="w-1/3">
                        <flux:input type="number" name="qty" label="Quantity Ordered" min="1" max="100" value="1" required />
                    </div>

                    <div class="p-4 bg-zinc-100 rounded-lg flex justify-between items-center">
                        <span class="text-sm font-medium text-zinc-600">Unit Estimate Price:</span>
                        <span class="text-2xl font-black text-indigo-600">
                            €{{ number_format($catalogPrice, 2) }}
                        </span>
                    </div>

                    <div class="pt-2">
                        <flux:button type="submit" variant="primary" class="w-full justify-center py-3 text-base font-bold" icon="shopping-cart">
                            Add Customized Combination to Cart
                        </flux:button>
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
                if(selectedOption) {
                    // Fallback block string fallback pattern parsing
                    let hex = selectedOption.getAttribute('data-hex') || '#f4f4f5';
                    panel.style.backgroundColor = hex;
                }
            }
            selector.addEventListener('change', updatePreviewColor);
            updatePreviewColor();
        });
    </script>
</x-layouts::main-content>