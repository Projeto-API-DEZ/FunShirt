<x-layouts::main-content title="Add New Design" heading="Create T‑shirt Image" subheading="Upload a new catalog design">
    <div class="max-w-2xl mx-auto py-6">
        <form method="POST" action="{{ route('admin.tshirt-images.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <flux:input name="name" label="Design Name" required value="{{ old('name') }}" />
            </div>

            <div class="mb-4">
                <flux:textarea name="description" label="Description" rows="3">{{ old('description') }}</flux:textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-zinc-700">Category</label>
                <select name="category_id" class="w-full bg-white border border-zinc-300 rounded-lg p-2 text-sm">
                    <option value="">No category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-zinc-700">Image file</label>
                <input type="file" name="image" accept="image/*" class="w-full" required>
            </div>

            <div class="flex space-x-2">
                <flux:button type="submit" variant="primary">Save Design</flux:button>
                <flux:button href="{{ route('admin.tshirt-images.index') }}" variant="ghost">Cancel</flux:button>
            </div>
        </form>
    </div>
</x-layouts::main-content>