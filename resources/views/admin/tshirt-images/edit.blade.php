<x-layouts::main-content title="Edit Design" heading="Edit T‑shirt Image" subheading="Update design details or replace the image">
    <div class="max-w-2xl mx-auto py-6">
        <form method="POST" action="{{ route('admin.tshirt-images.update', $tshirtImage) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-4">
                <flux:input name="name" label="Design Name" required value="{{ old('name', $tshirtImage->name) }}" />
            </div>

            <div class="mb-4">
                <flux:textarea name="description" label="Description" rows="3">{{ old('description', $tshirtImage->description) }}</flux:textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-zinc-700">Category</label>
                <select name="category_id" class="w-full bg-white border border-zinc-300 rounded-lg p-2 text-sm">
                    <option value="">No category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $tshirtImage->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-zinc-700">Current image</label>
                <img src="{{ asset('storage/tshirt_images/'.$tshirtImage->image_url) }}" class="h-20 object-contain mb-2">
                <label class="block text-sm font-medium mb-1 text-zinc-700">Replace image (optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full">
            </div>

            <div class="flex space-x-2">
                <flux:button type="submit" variant="primary">Update Design</flux:button>
                <flux:button href="{{ route('admin.tshirt-images.index') }}" variant="ghost">Cancel</flux:button>
            </div>
        </form>
    </div>
</x-layouts::main-content>