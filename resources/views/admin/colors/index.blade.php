<x-layouts::main-content title="Manage Colors" heading="T-Shirt Colors" subheading="Add, edit or remove available colors">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @if ($errors->has('color_delete'))
            <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first('color_delete') }}
            </div>
        @endif

        <div class="mb-4 flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-zinc-900">All Colors</h2>
            <a href="{{ route('admin.colors.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                Add Color
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-zinc-200">
                <thead class="bg-zinc-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Preview</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-zinc-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($colors as $color)
                        @php($previewCode = str_starts_with((string) $color->code, '#') ? $color->code : '#' . $color->code)
                        @php($isProtected = (int) ($color->order_items_usage_count ?? 0) > 0 || collect(session('cart', []))->contains(fn ($item) => ($item['color_code'] ?? null) === $color->code))
                        <tr>
                            <td class="px-6 py-4 font-mono text-sm text-zinc-700">{{ $previewCode }}</td>
                            <td class="px-6 py-4 text-sm text-zinc-900">{{ $color->name }}</td>
                            <td class="px-6 py-4">
                                <div class="h-6 w-6 rounded-full border border-zinc-300" style="background-color: {{ $previewCode }}"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.colors.edit', ['color' => rawurlencode($color->code)]) }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                                        Edit
                                    </a>
                                    @if ($isProtected)
                                        <span class="inline-flex items-center justify-center rounded-lg bg-zinc-100 px-3 py-1.5 text-sm font-medium text-zinc-500" title="Protected because it is already used by orders or by the current cart session.">
                                            Protected
                                        </span>
                                    @else
                                        <form action="{{ route('admin.colors.destroy', ['color' => rawurlencode($color->code)]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 transition hover:bg-red-100" onclick="return confirm('Delete this color?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-zinc-500">No colors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $colors->links() }}
        </div>
    </div>
</x-layouts::main-content>
