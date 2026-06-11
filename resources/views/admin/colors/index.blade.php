<x-layouts::main-content title="Manage Colors" heading="T‑shirt Colors" subheading="Add, edit or remove available colors">
    <div class="max-w-7xl mx-auto py-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold">All Colors</h2>
            <flux:button href="{{ route('admin.colors.create') }}" variant="primary">+ Add Color</flux:button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200">
                <thead class="bg-zinc-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Preview</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @foreach($colors as $color)
                    <tr>
                        <td class="px-6 py-4 font-mono">{{ $color->code }}</td>
                        <td class="px-6 py-4">{{ $color->name }}</td>
                        <td class="px-6 py-4">
                            <div class="w-6 h-6 rounded-full border border-zinc-300" style="background-color: {{ $color->code }}"></div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <flux:button size="sm" href="{{ route('admin.colors.edit', $color) }}" variant="outline">Edit</flux:button>
                            <form action="{{ route('admin.colors.destroy', $color) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <flux:button type="submit" size="sm" variant="danger" onclick="return confirm('Delete this color?')">Delete</flux:button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $colors->links() }}
        </div>
    </div>
</x-layouts::main-content>