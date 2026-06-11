<x-layouts::main-content title="Edit Design" heading="Edit T‑shirt Image" subheading="Update design details or replace the image">
    <div class="max-w-2xl mx-auto py-6">
        <form method="POST" action="{{ route('admin.tshirt-images.update', $tshirtImage) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-1 text-zinc-700">Design Name</label>
                <input id="name" name="name" type="text" required value="{{ old('name', $tshirtImage->name) }}" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-1 text-zinc-700">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('description', $tshirtImage->description) }}</textarea>
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
                <img src="{{ route('public.storage', ['path' => 'tshirt_images/' . $tshirtImage->image_url]) }}" class="h-20 object-contain mb-2" alt="{{ $tshirtImage->name }}">
                <label class="block text-sm font-medium mb-1 text-zinc-700">Replace image (optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full">
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">Update Design</button>
                <a href="{{ route('admin.tshirt-images.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts::main-content>
