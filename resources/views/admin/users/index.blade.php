@php
    $filters = $filters ?? ['search' => '', 'role' => '', 'status' => ''];

    $roleLabels = [
        'C' => 'Customer',
        'F' => 'Staff',
        'A' => 'Admin',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">User Management</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
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

            <section
                class="rounded-2xl border px-6 py-6 shadow-sm"
                style="background: var(--app-surface); border-color: var(--app-border);"
            >
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em]" style="color: var(--app-muted);">Admin Backend</p>
                        <h3 class="text-2xl font-semibold leading-tight" style="color: var(--app-text);">User Management</h3>
                        <p class="max-w-2xl text-sm" style="color: var(--app-muted);">
                            Search, review and maintain all platform accounts from one backend table.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.users.create') }}"
                        class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:opacity-95"
                        style="background: #4f46e5;"
                        wire:navigate
                    >
                        Create User
                    </a>
                </div>
            </section>

            <section
                class="overflow-hidden rounded-2xl border shadow-sm"
                style="background: var(--app-surface); border-color: var(--app-border);"
            >
                <div class="border-b px-6 py-5" style="border-color: var(--app-border); background: var(--app-surface-2);">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                        <div>
                            <h3 class="text-base font-semibold" style="color: var(--app-text);">Users Directory</h3>
                            <p class="mt-1 text-sm" style="color: var(--app-muted);">
                                Showing {{ $users->count() }} {{ \Illuminate\Support\Str::plural('user', $users->count()) }}
                                @if ($filters['search'] !== '' || $filters['role'] !== '' || $filters['status'] !== '')
                                    after applying filters.
                                @else
                                    from the current database.
                                @endif
                            </p>
                        </div>

                        <form action="{{ route('admin.users.index') }}" method="GET" class="grid gap-3 md:grid-cols-[minmax(0,1.4fr)_180px_180px_auto_auto]">
                            <div>
                                <label for="search" class="mb-1 block text-xs font-medium uppercase tracking-wide" style="color: var(--app-muted);">Search</label>
                                <input
                                    id="search"
                                    name="search"
                                    type="text"
                                    value="{{ $filters['search'] }}"
                                    placeholder="Name or email"
                                    class="block w-full rounded-xl border px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                                >
                            </div>

                            <div>
                                <label for="role" class="mb-1 block text-xs font-medium uppercase tracking-wide" style="color: var(--app-muted);">Role</label>
                                <select
                                    id="role"
                                    name="role"
                                    class="block w-full rounded-xl border px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                                >
                                    <option value="">All Roles</option>
                                    <option value="C" @selected($filters['role'] === 'C')>Customer</option>
                                    <option value="F" @selected($filters['role'] === 'F')>Staff</option>
                                    <option value="A" @selected($filters['role'] === 'A')>Admin</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="mb-1 block text-xs font-medium uppercase tracking-wide" style="color: var(--app-muted);">Status</label>
                                <select
                                    id="status"
                                    name="status"
                                    class="block w-full rounded-xl border px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                                >
                                    <option value="">All Status</option>
                                    <option value="active" @selected($filters['status'] === 'active')>Active</option>
                                    <option value="blocked" @selected($filters['status'] === 'blocked')>Blocked</option>
                                </select>
                            </div>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:opacity-95 md:self-end"
                                style="background: #4f46e5;"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route('admin.users.index') }}"
                                class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-medium md:self-end"
                                style="background: var(--app-surface); border: 1px solid var(--app-border); color: var(--app-muted);"
                                wire:navigate
                            >
                                Reset
                            </a>
                        </form>
                    </div>
                </div>

                @if ($users->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border text-lg font-semibold" style="border-color: var(--app-border); color: var(--app-muted);">
                            0
                        </div>
                        <h3 class="mt-5 text-lg font-semibold" style="color: var(--app-text);">No users found</h3>
                        <p class="mt-2 text-sm" style="color: var(--app-muted);">
                            Adjust the current filters or clear the search to see more accounts.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full table-fixed">
                            <colgroup>
                                <col class="w-[34%]">
                                <col class="w-[12%]">
                                <col class="w-[12%]">
                                <col class="w-[14%]">
                                <col class="w-[12%]">
                                <col class="w-[16%]">
                            </colgroup>
                            <thead style="background: var(--app-surface-2);">
                                <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em]" style="color: var(--app-muted);">
                                    <th class="px-6 py-4">User</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4">Gender</th>
                                    <th class="px-6 py-4">Email Verified</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="border-t align-top" style="border-color: var(--app-border);">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full border text-sm font-semibold uppercase"
                                                     style="border-color: var(--app-border); background: {{ $user->hasUploadedPhoto() ? 'transparent' : '#4f46e5' }}; color: {{ $user->hasUploadedPhoto() ? 'transparent' : '#ffffff' }};">
                                                    @if ($user->hasUploadedPhoto())
                                                        <img
                                                            src="{{ $user->photoFullUrl }}"
                                                            alt=""
                                                            class="block h-full w-full object-cover object-center"
                                                        >
                                                    @else
                                                        {{ $user->initials() }}
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-semibold" style="color: var(--app-text);">{{ $user->name }}</p>
                                                    <p class="truncate text-sm" style="color: var(--app-muted);">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-sm" style="color: var(--app-text);">
                                            {{ $roleLabels[$user->user_type] ?? $user->user_type }}
                                        </td>
                                        <td class="px-6 py-5 text-sm uppercase" style="color: var(--app-text);">
                                            {{ $user->gender ?: '—' }}
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium"
                                                  style="background: {{ $user->email_verified_at ? '#ecfdf5' : '#fff7ed' }}; color: {{ $user->email_verified_at ? '#047857' : '#c2410c' }};">
                                                {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium"
                                                  style="background: {{ $user->blocked ? '#fef2f2' : '#eff6ff' }}; color: {{ $user->blocked ? '#b91c1c' : '#1d4ed8' }};">
                                                {{ $user->blocked ? 'Blocked' : 'Active' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex flex-wrap gap-2">
                                                <a
                                                    href="{{ route('admin.users.show', $user) }}"
                                                    class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium"
                                                    style="background: var(--app-surface-2); color: var(--app-muted);"
                                                    wire:navigate
                                                >
                                                    Show
                                                </a>
                                                <a
                                                    href="{{ route('admin.users.edit', $user) }}"
                                                    class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium"
                                                    style="background: var(--app-surface-2); color: var(--app-muted);"
                                                    wire:navigate
                                                >
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete {{ $user->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium"
                                                        style="background: #fef2f2; color: #b91c1c;"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
