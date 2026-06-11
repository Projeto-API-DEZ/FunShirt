<x-layouts::main-content title="Manage T‑shirt Images" heading="Catalog Images" subheading="Add, edit or remove t‑shirt designs">
    <div class="max-w-7xl mx-auto py-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold">All Catalog Designs</h2>
            <flux:button href="{{ route('admin.tshirt-images.create') }}" variant="primary">+ Add New Design</flux:button>
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
                            <img src="{{ asset('storage/tshirt_images/'.$image->image_url) }}" class="h-12 w-12 object-cover rounded">
                        </td>
                        <td class="px-6 py-4 font-medium">{{ $image->name }}</td>
                        <td class="px-6 py-4">{{ $image->category?->name ?? 'Uncategorized' }}</td>
                        <td class="px-6 py-4 max-w-xs truncate">{{ $image->description }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <flux:button size="sm" href="{{ route('admin.tshirt-images.edit', $image) }}" variant="outline">Edit</flux:button>
                            <form action="{{ route('admin.tshirt-images.destroy', $image) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <flux:button type="submit" size="sm" variant="danger" onclick="return confirm('Delete this design?')">Delete</flux:button>
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