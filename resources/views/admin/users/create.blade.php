<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Create User</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border px-6 py-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em]" style="color: var(--app-muted);">Admin Backend</p>
                    <h3 class="text-2xl font-semibold leading-tight" style="color: var(--app-text);">Create User</h3>
                    <p class="max-w-2xl text-sm" style="color: var(--app-muted);">
                        Start from an empty form. Billing and payment fields are optional.
                    </p>
                </div>
            </section>

            @if ($errors->any())
                <div class="rounded-2xl border px-5 py-4 text-sm" style="background: #fef2f2; border-color: #fecaca; color: #991b1b;">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                @include('admin.users.partials.fields', ['mode' => 'create', 'user' => $user])

                <div class="flex flex-wrap gap-3 border-t pt-6" style="border-color: var(--app-border);">
                    <button type="submit" class="rounded-full px-4 py-2 text-sm font-medium text-white" style="background: #4f46e5;">
                        Save User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="rounded-full px-4 py-2 text-sm font-medium" style="background: var(--app-surface-2); color: var(--app-muted);" wire:navigate>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
