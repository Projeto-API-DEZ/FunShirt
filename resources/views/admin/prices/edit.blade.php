<x-layouts::main-content title="Configure Prices" heading="Price Settings" subheading="Set unit prices and discount thresholds">
    <div class="max-w-2xl mx-auto py-6">
        <form method="POST" action="{{ route('admin.prices.update') }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:input name="unit_price_catalog" label="Unit Price (Catalog)" type="number" step="0.01" value="{{ old('unit_price_catalog', $price->unit_price_catalog) }}" required />
                </div>
                <div>
                    <flux:input name="unit_price_own" label="Unit Price (Custom)" type="number" step="0.01" value="{{ old('unit_price_own', $price->unit_price_own) }}" required />
                </div>
                <div>
                    <flux:input name="unit_price_catalog_discount" label="Discount Price (Catalog)" type="number" step="0.01" value="{{ old('unit_price_catalog_discount', $price->unit_price_catalog_discount) }}" required />
                </div>
                <div>
                    <flux:input name="unit_price_own_discount" label="Discount Price (Custom)" type="number" step="0.01" value="{{ old('unit_price_own_discount', $price->unit_price_own_discount) }}" required />
                </div>
                <div class="md:col-span-2">
                    <flux:input name="qty_discount" label="Quantity for Discount" type="number" value="{{ old('qty_discount', $price->qty_discount) }}" required />
                    <p class="text-xs text-zinc-500 mt-1">When quantity reaches or exceeds this value, discount prices apply.</p>
                </div>
            </div>

            <div class="mt-6 flex space-x-2">
                <flux:button type="submit" variant="primary">Update Prices</flux:button>
                <flux:button href="{{ route('admin.categories.index') }}" variant="ghost">Cancel</flux:button>
            </div>
        </form>
    </div>
</x-layouts::main-content>