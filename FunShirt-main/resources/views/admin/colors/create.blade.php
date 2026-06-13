<x-layouts::main-content title="Add New Color" heading="Create Color" subheading="Add a new T-shirt color option">
    <div class="mx-auto max-w-xl px-4 py-6 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.colors.store') }}" class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="code" class="mb-1 block text-sm font-medium text-zinc-700">Color Code</label>
                    <input id="code" name="code" type="text" value="{{ old('code') }}" placeholder="#ff0000 or red" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-zinc-500">Use a CSS color value or hexadecimal code.</p>
                </div>

                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Display Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Red" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                    Save Color
                </button>
                <a href="{{ route('admin.colors.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-layouts::main-content>
