@props([
    'title' => null,
    'heading' => null,
    'subheading' => null
])

<x-layouts.app>
    @if($title) @section('title', " - {$title}") @endif

    <div class="max-w-7xl mx-auto space-y-6">
        @if($heading || $subheading)
            <div class="border-b border-zinc-200 dark:border-zinc-800 pb-4">
                @if($heading)
                    <h1 class="text-3xl font-extrabold text-zinc-950 dark:text-white tracking-tight">{{ $heading }}</h1>
                @endif
                @if($subheading)
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 font-light">{{ $subheading }}</p>
                @endif
            </div>
        @endif

        <div class="w-full">
            {{ $slot }}
        </div>
    </div>
</x-layouts.app>