<x-layouts::main-content title="Manage T‑shirt Images" heading="Catalog Images" subheading="Add, edit or remove t‑shirt designs">
    <div class="max-w-7xl mx-auto py-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold">All Catalog Designs</h2>
            <a href="{{ route('admin.tshirt-images.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                + Add New Design
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200">
                <thead class="bg-zinc-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @foreach($images as $image)
                    <tr>
                        <td class="px-6 py-4">
                            <img src="{{ route('public.storage', ['path' => 'tshirt_images/' . $image->image_url]) }}" class="h-12 w-12 object-cover rounded" alt="{{ $image->name }}">
                        </td>
                        <td class="px-6 py-4 font-medium">{{ $image->name }}</td>
                        <td class="px-6 py-4">{{ $image->category?->name ?? 'Uncategorized' }}</td>
                        <td class="px-6 py-4 max-w-xs truncate">{{ $image->description }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.tshirt-images.edit', $image) }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                                Edit
                            </a>
                            <form action="{{ route('admin.tshirt-images.destroy', $image) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 transition hover:bg-red-100" onclick="return confirm('Delete this design?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $images->links() }}
        </div>
    </div>
</x-layouts::main-content>
