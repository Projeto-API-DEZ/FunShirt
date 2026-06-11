<x-layouts::main-content title="Edit Color" heading="Edit Color" subheading="Update color details">
    <div class="max-w-lg mx-auto py-6">
        <form method="POST" action="{{ route('admin.colors.update', $color) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <flux:input name="code" label="Color Code (CSS color)" required value="{{ old('code', $color->code) }}" />
            </div>

            <div class="mb-4">
                <flux:input name="name" label="Display Name" required value="{{ old('name', $color->name) }}" />
            </div>

            <div class="mb-4 flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full border" style="background-color: {{ $color->code }}"></div>
                <span class="text-sm">Preview</span>
            </div>

            <div class="flex space-x-2">
                <flux:button type="submit" variant="primary">Update Color</flux:button>
                <flux:button href="{{ route('admin.colors.index') }}" variant="ghost">Cancel</flux:button>
            </div>
        </form>
    </div>
</x-layouts::main-content>