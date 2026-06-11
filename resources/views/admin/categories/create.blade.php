<x-layouts::main-content title="Add Category" heading="Create Category" subheading="Add a new t‑shirt category">
    <div class="max-w-2xl mx-auto py-6">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <flux:input name="name" label="Category Name" required value="{{ old('name') }}" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-zinc-700">Category Image (optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full">
            </div>

            <div class="flex space-x-2">
                <flux:button type="submit" variant="primary">Save Category</flux:button>
                <flux:button href="{{ route('admin.categories.index') }}" variant="ghost">Cancel</flux:button>
            </div>
        </form>
    </div>
</x-layouts::main-content>