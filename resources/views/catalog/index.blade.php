<x-layouts::main-content title="Catalog" heading="T-Shirt Catalog" subheading="Browse and customize our collection of designs">
    <div class="max-w-7xl mx-auto py-4">
        
        <div class="mb-8 p-6 bg-zinc-50 border border-zinc-200 rounded-xl shadow-sm">
            <form method="GET" action="{{ route('catalog.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <flux:input name="search" label="Search Designs" value="{{ request('search') }}" placeholder="Search name or description..." />
                
                <div>
                    <label class="block text-sm font-medium mb-1 text-zinc-700">Category</label>
                    <select name="category" class="w-full bg-white border border-zinc-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex space-x-2">
                    <flux:button type="submit" variant="primary" class="flex-1">Apply Filters</flux:button>
                    <flux:button href="{{ route('catalog.index') }}" variant="ghost">Reset</flux:button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($images as $image)
                <div class="bg-zinc-50 border border-zinc-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between group transition hover:shadow-md">
                    
                    <div class="bg-zinc-100 p-4 flex items-center justify-center relative aspect-square">
                        @if($image->image_url)
                            <img src="{{ asset('storage/tshirt_images/' . $image->image_url) }}" alt="{{ $image->name }}" class="max-h-full max-w-full object-contain transform group-hover:scale-105 transition duration-300">
                        @else
                            <flux:icon.photo class="size-16 text-zinc-400" />
                        @endif
                        
                        <span class="absolute top-2 right-2 px-2 py-0.5 bg-indigo-600 text-white font-bold text-xs rounded-full shadow-sm">
                            Catalog Design
                        </span>
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div class="mb-4">
                            <h4 class="font-bold text-lg tracking-tight truncate text-zinc-950">{{ $image->name }}</h4>
                            <p class="text-xs text-zinc-500 mt-0.5 italic">Category: {{ $image->category?->name ?? 'Uncategorized' }}</p>
                            <p class="text-sm font-light text-zinc-600 mt-2 line-clamp-2 leading-relaxed">
                                {{ $image->description }}
                            </p>
                        </div>

                        <div class="pt-2 border-t border-zinc-100 flex items-center justify-between">
                            <span class="text-xl font-black text-zinc-900">
                                €{{ number_format($catalogPrice, 2) }}
                            </span>
                            <flux:button size="sm" variant="primary" href="{{ route('catalog.show', $image) }}" icon="eye">
                                Configure
                            </flux:button>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-zinc-50 rounded-xl border border-dashed border-zinc-300">
                    <flux:icon.magnifying-glass class="mx-auto size-12 text-zinc-400" />
                    <h3 class="mt-4 font-bold text-lg text-zinc-700">No designs found</h3>
                    <p class="text-sm text-zinc-500 mt-1">Try tweaking your search parameters or category filter terms.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $images->links() }}
        </div>

    </div>
</x-layouts::main-content>