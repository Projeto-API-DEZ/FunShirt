<x-layouts::main-content title="Catalog" heading="T-Shirt Catalog" subheading="Browse and customize our collection of designs">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <section class="mb-8 rounded-xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm">
            <form method="GET" action="{{ route('catalog.index') }}" class="space-y-5">
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
                    <div class="mb-2 flex items-center justify-between gap-4">
                        <label class="block text-sm font-medium text-zinc-700">Category</label>
                        <span class="text-xs text-zinc-500">Select one category filter</span>
                    </div>

                    <input type="hidden" name="category" id="category" value="{{ request('category') }}">

                    <div class="overflow-hidden transition-all duration-200" id="category-filter-wrap" style="max-height: 160px;">
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                data-category-value=""
                                class="category-filter group flex w-[75px] min-h-[74px] flex-col items-center justify-center gap-1.5 rounded-xl border px-2 py-2 text-center transition {{ request('category') ? 'border-zinc-300 bg-white text-zinc-700 hover:border-indigo-400 hover:bg-indigo-50' : 'border-indigo-500 bg-indigo-50 text-indigo-700' }}"
                            >
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-100 text-[11px] font-semibold">All</span>
                                <span class="line-clamp-2 text-[11px] font-medium leading-4">All Categories</span>
                            </button>

                            @foreach ($categories as $category)
                                @php($isSelectedCategory = (string) request('category') === (string) $category->id)
                                <button
                                    type="button"
                                    data-category-value="{{ $category->id }}"
                                    class="category-filter category-filter-item group flex w-[75px] min-h-[74px] flex-col items-center justify-center gap-1.5 rounded-xl border px-2 py-2 text-center transition {{ $isSelectedCategory ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-zinc-300 bg-white text-zinc-700 hover:border-indigo-400 hover:bg-indigo-50' }}"
                                >
                                    @if ($category->image_url)
                                        <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100">
                                            <img
                                                src="{{ route('public.storage', ['path' => 'categories/' . $category->image_url]) }}"
                                                alt="{{ $category->name }}"
                                                class="h-full w-full object-cover"
                                            >
                                        </span>
                                    @else
                                        <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-dashed border-zinc-300 bg-zinc-100 text-[11px] font-semibold text-zinc-400">
                                            C
                                        </span>
                                    @endif

                                    <span class="line-clamp-2 text-[11px] font-medium leading-4">{{ $category->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-3 flex justify-end">
                        <button
                            type="button"
                            id="toggle-category-limit"
                            class="hidden items-center justify-center rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-100"
                            data-expanded="false"
                        >
                            Show More Categories
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                        Apply Filters
                    </button>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                        Reset
                    </a>
                </div>
            </form>
            <script>
                (() => {
                    const form = document.currentScript?.closest('section')?.querySelector('form');
                    const hiddenInput = form?.querySelector('#category');
                    const buttons = form?.querySelectorAll('.category-filter');
                    const toggleLimitButton = form?.querySelector('#toggle-category-limit');
                    const categoryWrap = form?.querySelector('#category-filter-wrap');
                    const collapsedHeight = 160;

                    if (!form || !hiddenInput || !buttons?.length) {
                        return;
                    }

                    const applyState = (selectedValue) => {
                        buttons.forEach((button) => {
                            const isActive = button.dataset.categoryValue === selectedValue;
                            button.classList.toggle('border-indigo-500', isActive);
                            button.classList.toggle('bg-indigo-50', isActive);
                            button.classList.toggle('text-indigo-700', isActive);
                            button.classList.toggle('border-zinc-300', !isActive);
                            button.classList.toggle('bg-white', !isActive);
                            button.classList.toggle('text-zinc-700', !isActive);
                            button.classList.toggle('hover:border-indigo-400', !isActive);
                            button.classList.toggle('hover:bg-indigo-50', !isActive);
                        });
                    };

                    applyState(hiddenInput.value);

                    buttons.forEach((button) => {
                        button.addEventListener('click', () => {
                            hiddenInput.value = button.dataset.categoryValue;
                            applyState(hiddenInput.value);
                        });
                    });

                    if (toggleLimitButton && categoryWrap) {
                        const hasOverflow = categoryWrap.scrollHeight > collapsedHeight;

                        if (hasOverflow) {
                            toggleLimitButton.classList.remove('hidden');
                            toggleLimitButton.classList.add('inline-flex');
                            categoryWrap.style.maxHeight = collapsedHeight + 'px';
                        } else {
                            categoryWrap.style.maxHeight = 'none';
                        }
                    }

                    toggleLimitButton?.addEventListener('click', () => {
                        const expanded = toggleLimitButton.dataset.expanded === 'true';
                        if (categoryWrap) {
                            categoryWrap.style.maxHeight = expanded ? collapsedHeight + 'px' : categoryWrap.scrollHeight + 'px';
                        }

                        toggleLimitButton.dataset.expanded = expanded ? 'false' : 'true';
                        toggleLimitButton.textContent = expanded ? 'Show More Categories' : 'Show Fewer Categories';
                    });
                })();
            </script>
        </section>

        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @forelse ($images as $image)
                <article class="group flex h-full flex-col overflow-hidden rounded-xl border border-zinc-200 bg-zinc-50 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="relative flex aspect-square items-center justify-center border-b border-zinc-200 p-4" style="background-color: var(--app-image-bg);">
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

                    </div>

                    <div class="flex flex-1 flex-col justify-between p-4">
                        <div>
                            <h3 class="truncate text-lg font-semibold text-zinc-900">{{ $image->name }}</h3>
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
