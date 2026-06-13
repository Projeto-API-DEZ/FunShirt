<x-layouts::main-content title="Add New Design" heading="Create T‑shirt Image" subheading="Upload a new catalog design">
    <div class="max-w-2xl mx-auto py-6">
        <form method="POST" action="{{ route('admin.tshirt-images.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-1 text-zinc-700">Design Name</label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-1 text-zinc-700">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
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
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">Save Design</button>
                <a href="{{ route('admin.tshirt-images.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts::main-content>
