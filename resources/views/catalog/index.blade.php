<x-layouts::main-content title="Catalog" heading="T-Shirt Catalog" subheading="Browse and customize our collection of designs">
    <div class="max-w-7xl mx-auto py-4">
        <div class="mb-8 rounded-xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm">
            <form method="GET" action="{{ route('catalog.index') }}" class="grid grid-cols-1 items-end gap-4 sm:grid-cols-3">
                <div>
                    <label for="search" class="mb-1 block text-sm font-medium text-zinc-700">Search Designs</label>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ request('search') }}"
                        placeholder="Search name or description..."
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label for="category" class="mb-1 block text-sm font-medium text-zinc-700">Category</label>
                    <select id="category" name="category" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                        Apply Filters
                    </button>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($images as $image)
                <div class="group flex flex-col justify-between overflow-hidden rounded-xl border border-zinc-200 bg-zinc-50 shadow-sm transition hover:shadow-md">
                    <div class="relative flex aspect-square items-center justify-center bg-zinc-100 p-4">
                        @if ($image->image_url)
                            <img src="{{ route('public.storage', ['path' => 'tshirt_images/' . $image->image_url]) }}" alt="{{ $image->name }}" class="max-h-full max-w-full object-contain transition duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-xl border border-dashed border-zinc-300 text-xs font-medium text-zinc-400">
                                No image
                            </div>
                        @endif

                        <span class="absolute top-2 right-2 rounded-full bg-indigo-600 px-2 py-0.5 text-xs font-bold text-white shadow-sm">
                            Catalog Design
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col justify-between p-4">
                        <div class="mb-4">
                            <h4 class="truncate text-lg font-bold tracking-tight text-zinc-950">{{ $image->name }}</h4>
                            <p class="mt-0.5 text-xs italic text-zinc-500">Category: {{ $image->category?->name ?? 'Uncategorized' }}</p>
                            <p class="mt-2 line-clamp-2 text-sm font-light leading-relaxed text-zinc-600">
                                {{ $image->description }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between border-t border-zinc-100 pt-2">
                            <span class="text-xl font-black text-zinc-900">
                                €{{ number_format($catalogPrice, 2) }}
                            </span>
                            <a href="{{ route('catalog.show', $image) }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                                Configure
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-zinc-300 bg-zinc-50 py-16 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full border border-dashed border-zinc-300 text-xs font-medium text-zinc-400">
                        0
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-zinc-700">No designs found</h3>
                    <p class="mt-1 text-sm text-zinc-500">Try tweaking your search parameters or category filter terms.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $images->links() }}
        </div>
    </div>
</x-layouts::main-content>
