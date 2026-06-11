<x-layouts::main-content title="Add New Color" heading="Create Color" subheading="Add a new t‑shirt color option">
    <div class="max-w-lg mx-auto py-6">
        <form method="POST" action="{{ route('admin.colors.store') }}">
            @csrf

            <div class="mb-4">
                <flux:input name="code" label="Color Code (CSS color)" required value="{{ old('code') }}" placeholder="e.g., #ff0000 or red" />
                <p class="text-xs text-zinc-500 mt-1">CSS color name or hex code</p>
            </div>

            <div class="mb-4">
                <flux:input name="name" label="Display Name" required value="{{ old('name') }}" placeholder="e.g., Red" />
            </div>

            <div class="flex space-x-2">
                <flux:button type="submit" variant="primary">Save Color</flux:button>
                <flux:button href="{{ route('admin.colors.index') }}" variant="ghost">Cancel</flux:button>
            </div>
        </form>
    </div>
</x-layouts::main-content>