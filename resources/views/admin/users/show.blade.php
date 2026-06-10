<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Show User</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border px-6 py-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em]" style="color: var(--app-muted);">Admin Backend</p>
                    <h3 class="text-2xl font-semibold leading-tight" style="color: var(--app-text);">{{ $user->name }}</h3>
                    <p class="max-w-2xl text-sm" style="color: var(--app-muted);">
                        Read-only view. Blocking controls stay out of this screen by design.
                    </p>
                </div>
            </section>

            <div class="rounded-2xl border p-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                @include('admin.users.partials.fields', ['mode' => 'show', 'user' => $user])

                <div class="mt-6 flex flex-wrap gap-3 border-t pt-6" style="border-color: var(--app-border);">
                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-full px-4 py-2 text-sm font-medium text-white" style="background: #4f46e5;" wire:navigate>
                        Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="rounded-full px-4 py-2 text-sm font-medium" style="background: var(--app-surface-2); color: var(--app-muted);" wire:navigate>
                        Back to Index
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
