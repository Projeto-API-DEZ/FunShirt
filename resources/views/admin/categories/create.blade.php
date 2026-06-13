<x-layouts::main-content title="Add Category" heading="Create Category" subheading="Add a new T-shirt category">
    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            @csrf

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_220px]">
                <div class="space-y-5">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Category Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="image" class="mb-1 block text-sm font-medium text-zinc-700">Category Image</label>
                        <input id="image" type="file" name="image" accept="image/*" class="block w-full text-sm text-zinc-600">
                        <p class="mt-1 text-xs text-zinc-500">Optional. Upload one image for this category.</p>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-700">Preview</label>
                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                        <div id="category-image-preview" class="flex h-40 w-full items-center justify-center overflow-hidden rounded-xl border border-zinc-200 bg-white">
                            <img id="category-image-preview-img" src="" alt="Category preview" class="hidden max-h-full max-w-full object-contain p-3">
                            <span id="category-image-preview-placeholder" class="text-xs font-medium text-zinc-400">No image selected</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                    Save Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        (() => {
            const input = document.getElementById('image');
            const image = document.getElementById('category-image-preview-img');
            const placeholder = document.getElementById('category-image-preview-placeholder');

            if (!input || !image || !placeholder) {
                return;
            }

            input.addEventListener('change', () => {
                const [file] = input.files || [];

                if (!file) {
                    image.removeAttribute('src');
                    image.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                    return;
                }

                image.src = URL.createObjectURL(file);
                image.classList.remove('hidden');
                placeholder.classList.add('hidden');
            });
        })();
    </script>
</x-layouts::main-content>
