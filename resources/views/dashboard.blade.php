<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="space-y-3 p-6 text-gray-900">
                    <p class="text-lg font-semibold">You are logged in.</p>
                    <p class="text-sm text-zinc-600">Redirecting to the catalog homepage in 5 seconds.</p>
                    <p class="text-sm text-zinc-500">
                        <a href="{{ route('catalog.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Go now
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = @json(route('catalog.index'));
        }, 5000);
    </script>
</x-app-layout>
