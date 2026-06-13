<x-app-layout>
    @include('admin.users.partials.theme')

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Edit User</h2>
    </x-slot>

    <div class="admin-user-theme py-8">
        <div class="mx-auto max-w-6xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border px-6 py-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em]" style="color: var(--app-muted);">Admin Backend</p>
                        <h3 class="text-2xl font-semibold leading-tight" style="color: var(--app-text);">{{ $user->name }}</h3>
                        <p class="max-w-2xl text-sm" style="color: var(--app-muted);">
                            Update the core account profile and access state. Avatar and customer billing details stay read-only here.
                        </p>
                    </div>

                    <a href="{{ route('admin.users.show', $user) }}" class="rounded-full px-4 py-2 text-sm font-medium" style="background: var(--app-surface-2); color: var(--app-muted);" wire:navigate>
                        Back to Show
                    </a>
                </div>
            </section>

            @if (session('status'))
                <div class="rounded-2xl border px-5 py-4 text-sm" style="background: #ecfdf5; border-color: #a7f3d0; color: #065f46;">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border px-5 py-4 text-sm" style="background: #fef2f2; border-color: #fecaca; color: #991b1b;">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                @include('admin.users.partials.fields', ['mode' => 'edit', 'user' => $user])

                <div class="flex flex-wrap border-t pt-6" style="gap: 10px; border-color: var(--app-border);">
                    <button type="submit" class="rounded-full px-4 py-2 text-sm font-medium text-white" style="background: #2563eb;">
                        Save Changes
                    </button>

                    <button
                        type="submit"
                        form="delete-user-form"
                        class="rounded-full px-4 py-2 text-sm font-medium"
                        style="background: #fef2f2; color: #b91c1c;"
                    >
                        Delete User
                    </button>

                    <a href="{{ route('admin.users.index') }}" class="rounded-full px-4 py-2 text-sm font-medium" style="background: var(--app-surface-2); color: var(--app-muted);" wire:navigate>
                        Cancel
                    </a>
                </div>
            </form>

            <form
                id="delete-user-form"
                action="{{ route('admin.users.destroy', $user) }}"
                method="POST"
                onsubmit="return confirm('Delete {{ $user->name }}?');"
            >
                @csrf
                @method('DELETE')
            </form>

            <form id="toggle-block-form" action="{{ route('admin.users.toggle-block', $user) }}" method="POST">
                @csrf
                @method('PATCH')
            </form>
        </div>
    </div>
</x-app-layout>
