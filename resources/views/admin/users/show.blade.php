<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Show User</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border px-6 py-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em]" style="color: var(--app-muted);">Admin Backend</p>
                        <h3 class="text-2xl font-semibold leading-tight" style="color: var(--app-text);">{{ $user->name }}</h3>
                        <p class="max-w-2xl text-sm" style="color: var(--app-muted);">
                            Read-only view for identity and account status.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}" class="rounded-full px-4 py-2 text-sm font-medium text-white" style="background: #4f46e5;" wire:navigate>
                            Edit
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="rounded-full px-4 py-2 text-sm font-medium" style="background: var(--app-surface-2); color: var(--app-muted);" wire:navigate>
                            Back to Index
                        </a>
                    </div>
                </div>
            </section>

            <div class="rounded-2xl border p-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                @include('admin.users.partials.fields', ['mode' => 'show', 'user' => $user])
            </div>
        </div>
    </div>
</x-app-layout>
