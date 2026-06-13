<x-layouts::main-content title="Catalog" heading="T-Shirt Catalog" subheading="Browse and customize our collection of designs">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <section class="mb-8 rounded-xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm">
            <form method="GET" action="{{ route('catalog.index') }}" class="grid grid-cols-1 items-end gap-4 lg:grid-cols-[minmax(0,1.6fr)_260px_auto]">
                <div>
                    <label for="search" class="mb-1 block text-sm font-medium text-zinc-700">Search Designs</label>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ request('search') }}"
                        placeholder="Search by name or description"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label for="category" class="mb-1 block text-sm font-medium text-zinc-700">Category</label>
                    <select id="category" name="category" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                        Apply Filters
                    </button>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                        Reset
                    </a>
                </div>
            </form>

            @if ($categories->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2">
                    <a
                        href="{{ route('catalog.index', request()->except(['category', 'page'])) }}"
                        class="inline-flex items-center rounded-full border px-3 py-1.5 text-sm font-medium transition hover:bg-zinc-100"
                        style="{{ request()->filled('category') ? 'border-color: var(--app-border); background: var(--app-surface); color: var(--app-text);' : 'border-color:#4f46e5;background:#4f46e5;color:#fff;' }}"
                    >
                        All
                    </a>

                    @foreach ($categories as $category)
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except('page'), ['category' => $category->id])) }}"
                            class="inline-flex items-center rounded-full border px-3 py-1.5 text-sm font-medium transition hover:bg-zinc-100"
                            style="{{ (string) request('category') === (string) $category->id ? 'border-color:#4f46e5;background:#4f46e5;color:#fff;' : 'border-color: var(--app-border); background: var(--app-surface); color: var(--app-text);' }}"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @forelse ($images as $image)
                <article class="group flex h-full flex-col overflow-hidden rounded-xl border border-zinc-200 bg-zinc-50 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="relative flex aspect-square items-center justify-center bg-zinc-100 p-4">
                        @if ($image->image_url)
                            <img
                                src="{{ route('public.storage', ['path' => 'tshirt_images/' . $image->image_url]) }}"
                                alt="{{ $image->name }}"
                                class="max-h-full max-w-full object-contain transition duration-300 group-hover:scale-105"
                            >
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-xl border border-dashed border-zinc-300 text-xs font-medium text-zinc-400">
                                No image
                            </div>
                        @endif

                        <span class="absolute right-3 top-3 rounded-full bg-indigo-600 px-2 py-1 text-xs font-semibold text-white">
                            Catalog
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col justify-between p-4">
                        <div>
                            <h3 class="truncate text-lg font-semibold text-zinc-950">{{ $image->name }}</h3>
                            <p class="mt-1 text-xs text-zinc-500">
                                {{ $image->category?->name ?? 'Uncategorized' }}
                            </p>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-zinc-600">
                                {{ $image->description ?: 'No description available.' }}
                            </p>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-zinc-200 pt-3">
                            <span class="text-xl font-bold text-zinc-900">
                                &euro;{{ number_format($catalogPrice, 2) }}
                            </span>
                            <a href="{{ route('catalog.show', $image) }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                                View Details
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-zinc-300 bg-zinc-50 px-6 py-16 text-center">
                    <h3 class="text-lg font-semibold text-zinc-700">No designs found</h3>
                    <p class="mt-2 text-sm text-zinc-500">Change the search text or category filter and try again.</p>
                </div>
            @endforelse
        </section>

        <div class="mt-8">
            {{ $images->links() }}
        </div>
    </div>
</x-layouts::main-content>
