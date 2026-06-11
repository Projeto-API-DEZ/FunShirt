<x-layouts::main-content title="Manage Categories" heading="Categories" subheading="Add, edit or remove t‑shirt categories">
    <div class="max-w-7xl mx-auto py-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold">All Categories</h2>
            <flux:button href="{{ route('admin.categories.create') }}" variant="primary">+ Add Category</flux:button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200">
                <thead class="bg-zinc-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Image</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @foreach($categories as $category)
                    <tr>
                        <td class="px-6 py-4">{{ $category->id }}</td>
                        <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                        <td class="px-6 py-4">
                            @if($category->image_url)
                                <img src="{{ asset('storage/categories/'.$category->image_url) }}" class="h-12 w-12 object-cover rounded">
                            @else
                                <span class="text-zinc-400 text-sm">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <flux:button size="sm" href="{{ route('admin.categories.edit', $category) }}" variant="outline">Edit</flux:button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <flux:button type="submit" size="sm" variant="danger" onclick="return confirm('Delete this category?')">Delete</flux:button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</x-layouts::main-content>