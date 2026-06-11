<x-layouts::main-content title="Edit Category" heading="Edit Category" subheading="Update category details">
    <div class="max-w-2xl mx-auto py-6">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-4">
                <flux:input name="name" label="Category Name" required value="{{ old('name', $category->name) }}" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-zinc-700">Current Image</label>
                @if($category->image_url)
                    <img src="{{ asset('storage/categories/'.$category->image_url) }}" class="h-20 mb-2">
                @else
                    <p class="text-zinc-500 text-sm mb-2">No image currently</p>
                @endif
                <label class="block text-sm font-medium mb-1 text-zinc-700">Replace Image (optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full">
            </div>

            <div class="flex space-x-2">
                <flux:button type="submit" variant="primary">Update Category</flux:button>
                <flux:button href="{{ route('admin.categories.index') }}" variant="ghost">Cancel</flux:button>
            </div>
        </form>
    </div>
</x-layouts::main-content>