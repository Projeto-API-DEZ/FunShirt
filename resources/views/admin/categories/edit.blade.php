<x-layouts::main-content title="Edit Category" heading="Edit Category" subheading="Update category details">
    <div class="mx-auto max-w-2xl px-4 py-6 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Category Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700">Current Image</label>
                    @if ($category->image_url)
                        <img src="{{ route('public.storage', ['path' => 'categories/' . $category->image_url]) }}" alt="{{ $category->name }}" class="mb-3 h-24 w-24 rounded-lg object-cover">
                    @else
                        <p class="mb-3 text-sm text-zinc-500">No image currently assigned.</p>
                    @endif

                    <label for="image" class="mb-1 block text-sm font-medium text-zinc-700">Replace Image</label>
                    <input id="image" type="file" name="image" accept="image/*" class="block w-full text-sm text-zinc-600">
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                    Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-layouts::main-content>
