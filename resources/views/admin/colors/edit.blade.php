<x-layouts::main-content title="Edit Color" heading="Edit Color" subheading="Update color details">
    <div class="mx-auto max-w-xl px-4 py-6 sm:px-6 lg:px-8">
        @php($displayCode = str_starts_with((string) old('code', $color->code), '#') ? old('code', $color->code) : '#' . old('code', $color->code))
        <form method="POST" action="{{ route('admin.colors.update', ['color' => rawurlencode($color->code)]) }}" class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label for="code" class="mb-1 block text-sm font-medium text-zinc-700">Color Code</label>
                    <input id="code" name="code" type="text" value="{{ $displayCode }}" placeholder="#A30B9EFF" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-zinc-500">Changing the color code will migrate linked order items to the new code.</p>
                </div>

                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Display Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $color->name) }}" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="flex items-center gap-3">
                    <div id="color-preview-edit" class="h-8 w-8 rounded-full border border-zinc-300" style="background-color: {{ $displayCode }}"></div>
                    <span class="text-sm text-zinc-600">Current preview</span>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                    Update Color
                </button>
                <a href="{{ route('admin.colors.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        (() => {
            const input = document.getElementById('code');
            const preview = document.getElementById('color-preview-edit');

            if (!input || !preview) {
                return;
            }

            const syncPreview = () => {
                preview.style.backgroundColor = input.value || '#f4f4f5';
            };

            input.addEventListener('input', syncPreview);
            syncPreview();
        })();
    </script>
</x-layouts::main-content>
