<x-layouts::main-content title="Configure Prices" heading="Price Settings" subheading="Set unit prices and discount thresholds">
    <div class="max-w-2xl mx-auto py-6">
        <form method="POST" action="{{ route('admin.prices.update') }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="unit_price_catalog" class="block text-sm font-medium mb-1 text-zinc-700">Unit Price (Catalog)</label>
                    <input id="unit_price_catalog" name="unit_price_catalog" type="number" step="0.01" value="{{ old('unit_price_catalog', $price->unit_price_catalog) }}" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="unit_price_own" class="block text-sm font-medium mb-1 text-zinc-700">Unit Price (Custom)</label>
                    <input id="unit_price_own" name="unit_price_own" type="number" step="0.01" value="{{ old('unit_price_own', $price->unit_price_own) }}" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="unit_price_catalog_discount" class="block text-sm font-medium mb-1 text-zinc-700">Discount Price (Catalog)</label>
                    <input id="unit_price_catalog_discount" name="unit_price_catalog_discount" type="number" step="0.01" value="{{ old('unit_price_catalog_discount', $price->unit_price_catalog_discount) }}" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="unit_price_own_discount" class="block text-sm font-medium mb-1 text-zinc-700">Discount Price (Custom)</label>
                    <input id="unit_price_own_discount" name="unit_price_own_discount" type="number" step="0.01" value="{{ old('unit_price_own_discount', $price->unit_price_own_discount) }}" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label for="qty_discount" class="block text-sm font-medium mb-1 text-zinc-700">Quantity for Discount</label>
                    <input id="qty_discount" name="qty_discount" type="number" value="{{ old('qty_discount', $price->qty_discount) }}" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-zinc-500 mt-1">When quantity reaches or exceeds this value, discount prices apply.</p>
                </div>
            </div>

            <div class="mt-6 flex space-x-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">Update Prices</button>
                <a href="{{ route('admin.prices.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts::main-content>
