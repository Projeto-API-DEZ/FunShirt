<x-layouts::main-content title="Add New Design" heading="Create T-shirt Image" subheading="Upload a new catalog design">
    <div class="mx-auto max-w-2xl py-6">
        <form method="POST" action="{{ route('admin.tshirt-images.store') }}" enctype="multipart/form-data" class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Design Name</label>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="description" class="mb-1 block text-sm font-medium text-zinc-700">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="category_id" class="mb-1 block text-sm font-medium text-zinc-700">Category</label>
                    <select id="category_id" name="category_id" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm">
                        <option value="">No category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="image" class="mb-1 block text-sm font-medium text-zinc-700">Image file</label>
                    <input id="image" type="file" name="image" accept="image/*" class="w-full" required>

                    <div class="mt-3">
                        <div id="design-image-preview" class="hidden h-40 w-40 overflow-hidden rounded-xl border border-zinc-200 bg-zinc-100">
                            <img id="design-image-preview-img" src="" alt="Design preview" class="h-full w-full object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex space-x-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">Save Design</button>
                <a href="{{ route('admin.tshirt-images.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        (() => {
            const input = document.getElementById('image');
            const wrapper = document.getElementById('design-image-preview');
            const image = document.getElementById('design-image-preview-img');

            if (!input || !wrapper || !image) {
                return;
            }

            input.addEventListener('change', () => {
                const [file] = input.files || [];

                if (!file) {
                    wrapper.classList.add('hidden');
                    image.removeAttribute('src');
                    return;
                }

                image.src = URL.createObjectURL(file);
                wrapper.classList.remove('hidden');
            });
        })();
    </script>
</x-layouts::main-content>
