<x-layouts::main-content title="Manage Categories" heading="Categories" subheading="Add, edit or remove T-shirt categories">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-zinc-900">All Categories</h2>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                Add Category
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-zinc-200">
                <thead class="bg-zinc-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Image</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-zinc-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 text-sm text-zinc-600">{{ $category->id }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-zinc-900">{{ $category->name }}</td>
                            <td class="px-6 py-4">
                                @if ($category->image_url)
                                    <img src="{{ route('public.storage', ['path' => 'categories/' . $category->image_url]) }}" alt="{{ $category->name }}" class="h-12 w-12 rounded-lg object-cover">
                                @else
                                    <span class="text-sm text-zinc-400">No image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 transition hover:bg-red-100" onclick="return confirm('Delete this category?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-zinc-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</x-layouts::main-content>
