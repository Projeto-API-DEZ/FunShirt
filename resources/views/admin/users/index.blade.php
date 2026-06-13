@php
    $filters = $filters ?? [
        'search' => '',
        'role' => '',
        'status' => '',
        'gender' => '',
        'verification' => '',
        'sort' => 'name_asc',
        'per_page' => '20',
    ];

    $roleLabels = [
        'C' => 'Customer',
        'F' => 'Staff',
        'A' => 'Admin',
    ];

    $userCount = method_exists($users, 'total') ? $users->total() : $users->count();
@endphp

<x-app-layout>
    @include('admin.users.partials.theme')

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">User Management</h2>
    </x-slot>

    <div class="admin-user-theme py-8">
        <div class="mx-auto max-w-[96rem] space-y-5" style="padding-left: 100px; padding-right: 100px;">
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

            <section class="rounded-2xl border px-6 py-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                    <div class="justify-self-start max-w-3xl space-y-2 text-left">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em]" style="color: var(--app-muted);">Admin Backend</p>
                        <h3 class="text-2xl font-semibold leading-tight" style="color: var(--app-text);">User Management</h3>
                        <p class="max-w-2xl text-sm" style="color: var(--app-muted);">
                            Search, review and maintain all platform accounts from one backend table.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.users.create') }}"
                        class="inline-flex w-full items-center justify-center self-start rounded-xl px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:opacity-95 sm:w-auto lg:justify-self-end"
                        style="background: #2563eb;"
                        wire:navigate
                    >
                        Create User
                    </a>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                <div class="border-b" style="padding: 24px; border-color: var(--app-border); background: var(--app-surface-2);">
                    <div class="flex flex-col" style="gap: 10px;">
                        <div class="text-left">
                            <h3 class="text-base font-semibold" style="color: var(--app-text);">Users Directory</h3>
                        </div>

                        <form action="{{ route('admin.users.index') }}" method="GET" class="grid gap-x-4 gap-y-4 md:grid-cols-2 xl:grid-cols-[minmax(0,1.95fr)_160px_160px_160px_180px_auto]">
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
                                <select id="role" name="role" class="block w-full rounded-xl border px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);">
                                    <option value="">All Roles</option>
                                    <option value="C" @selected($filters['role'] === 'C')>Customer</option>
                                    <option value="F" @selected($filters['role'] === 'F')>Staff</option>
                                    <option value="A" @selected($filters['role'] === 'A')>Admin</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="mb-1 block text-xs font-medium uppercase tracking-wide" style="color: var(--app-muted);">Status</label>
                                <select id="status" name="status" class="block w-full rounded-xl border px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);">
                                    <option value="">All Status</option>
                                    <option value="active" @selected($filters['status'] === 'active')>Active</option>
                                    <option value="blocked" @selected($filters['status'] === 'blocked')>Blocked</option>
                                </select>
                            </div>

                            <div>
                                <label for="gender" class="mb-1 block text-xs font-medium uppercase tracking-wide" style="color: var(--app-muted);">Gender</label>
                                <select id="gender" name="gender" class="block w-full rounded-xl border px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);">
                                    <option value="">All Gender</option>
                                    <option value="M" @selected($filters['gender'] === 'M')>Male</option>
                                    <option value="F" @selected($filters['gender'] === 'F')>Female</option>
                                </select>
                            </div>

                            <div>
                                <label for="verification" class="mb-1 block text-xs font-medium uppercase tracking-wide" style="color: var(--app-muted);">Email Verification</label>
                                <select id="verification" name="verification" class="block w-full rounded-xl border px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);">
                                    <option value="">All</option>
                                    <option value="verified" @selected($filters['verification'] === 'verified')>Verified</option>
                                    <option value="pending" @selected($filters['verification'] === 'pending')>Pending</option>
                                </select>
                            </div>

                            <div class="flex items-end justify-end" style="gap: 12px;">
                                <input type="hidden" name="per_page" value="{{ $filters['per_page'] }}">
                                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-medium" style="background: var(--app-surface); border: 1px solid var(--app-border); color: var(--app-muted);" wire:navigate>
                                    Reset
                                </a>

                                <button type="submit" class="inline-flex min-w-[96px] items-center justify-center rounded-xl px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:opacity-95" style="background: #2563eb;">
                                    Search
                                </button>
                            </div>
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
                    <div class="overflow-x-auto" style="padding: 0;">
                        <table class="w-full table-fixed">
                            <colgroup>
                                <col class="w-[24%]">
                                <col class="w-[9%]">
                                <col class="w-[7%]">
                                <col class="w-[10%]">
                                <col class="w-[10%]">
                                <col class="w-[10%]">
                                <col class="w-[30%]">
                            </colgroup>
                            <thead style="background: var(--app-surface-2);">
                                <tr class="text-left text-xs font-semibold uppercase tracking-[0.16em]" style="color: var(--app-muted);">
                                    <th class="px-5 py-4">User</th>
                                    <th class="px-4 py-4">Role</th>
                                    <th class="px-4 py-4">Gender</th>
                                    <th class="px-4 py-4">Email Verified</th>
                                    <th class="px-4 py-4">Status</th>
                                    <th class="px-4 py-4">Created</th>
                                    <th class="px-3 py-4">
                                        <div class="flex items-center justify-end whitespace-nowrap" style="gap: 50px; padding-right: 50px;">
                                            <div class="shrink-0 text-left">
                                                Actions
                                            </div>

                                            <div class="shrink-0">
                                                <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center normal-case tracking-normal" style="gap: 5px;">
                                                    <input type="hidden" name="search" value="{{ $filters['search'] }}">
                                                    <input type="hidden" name="role" value="{{ $filters['role'] }}">
                                                    <input type="hidden" name="status" value="{{ $filters['status'] }}">
                                                    <input type="hidden" name="gender" value="{{ $filters['gender'] }}">
                                                    <input type="hidden" name="verification" value="{{ $filters['verification'] }}">
                                                    <input type="hidden" name="per_page" value="{{ $filters['per_page'] }}">
                                                    <label for="header-sort" class="text-xs font-medium uppercase tracking-wide" style="color: var(--app-muted);">Sort</label>
                                                    <select
                                                        id="header-sort"
                                                        name="sort"
                                                        onchange="this.form.submit()"
                                                        class="w-[108px] rounded-xl border px-2.5 py-2 text-sm font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                                                    >
                                                        <option value="name_asc" @selected($filters['sort'] === 'name_asc')>Name A-Z</option>
                                                        <option value="name_desc" @selected($filters['sort'] === 'name_desc')>Name Z-A</option>
                                                        <option value="created_desc" @selected($filters['sort'] === 'created_desc')>Newest First</option>
                                                        <option value="created_asc" @selected($filters['sort'] === 'created_asc')>Oldest First</option>
                                                        <option value="email_asc" @selected($filters['sort'] === 'email_asc')>Email A-Z</option>
                                                        <option value="email_desc" @selected($filters['sort'] === 'email_desc')>Email Z-A</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="border-t align-top" style="border-color: var(--app-border);">
                                        <td class="px-6 py-5">
                                                <div class="flex min-w-0 items-center gap-4">
                                                    <div class="flex shrink-0 items-center justify-center overflow-hidden rounded-full border text-sm font-semibold uppercase leading-none" style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; max-width: 50px; max-height: 50px; border-color: var(--app-border); background: {{ $user->hasUploadedPhoto() ? 'transparent' : '#2563eb' }}; color: {{ $user->hasUploadedPhoto() ? 'transparent' : '#ffffff' }};">
                                                    @if ($user->hasUploadedPhoto())
                                                        <img src="{{ $user->photoFullUrl }}" alt="" class="block object-cover object-center" style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; max-width: 50px; max-height: 50px;">
                                                    @else
                                                        {{ $user->initials() }}
                                                    @endif
                                                </div>
                                                <div class="min-w-0 max-w-[300px] flex-1 overflow-hidden lg:max-w-[360px]">
                                                    <p class="truncate text-sm font-semibold" style="color: var(--app-text);" title="{{ $user->name }}">{{ $user->name }}</p>
                                                    <p class="truncate text-sm" style="color: var(--app-muted);" title="{{ $user->email }}">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-sm" style="color: var(--app-text);">
                                            {{ $roleLabels[$user->user_type] ?? $user->user_type }}
                                        </td>
                                        <td class="px-6 py-5 text-sm uppercase" style="color: var(--app-text);">
                                            {{ $user->gender ?: '-' }}
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium" style="background: {{ $user->email_verified_at ? '#ecfdf5' : '#fff7ed' }}; color: {{ $user->email_verified_at ? '#047857' : '#c2410c' }};">
                                                {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium" style="background: {{ $user->blocked ? '#fef2f2' : '#eff6ff' }}; color: {{ $user->blocked ? '#b91c1c' : '#2563eb' }};">
                                                {{ $user->blocked ? 'Blocked' : 'Active' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-sm" style="color: var(--app-text);">
                                            {{ optional($user->created_at)->format('Y-m-d') ?: '-' }}
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex flex-wrap justify-end gap-2" style="padding-right: 50px;">
                                                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium" style="background: var(--app-surface-2); color: var(--app-muted);" wire:navigate>
                                                    Show
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium" style="background: var(--app-surface-2); color: var(--app-muted);" wire:navigate>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete {{ $user->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium" style="background: #fef2f2; color: #b91c1c;">
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

                    <div class="border-t px-6 py-4" style="border-color: var(--app-border); background: var(--app-surface);">
                        @if (method_exists($users, 'links'))
                            <div>
                                {{ $users->links() }}
                            </div>
                        @else
                            <div class="text-sm" style="color: var(--app-muted);">
                                Showing all {{ $userCount }} users.
                            </div>
                        @endif

                        <form action="{{ route('admin.users.index') }}" method="GET" class="mt-4 flex items-center" style="gap: 15px;">
                            <input type="hidden" name="search" value="{{ $filters['search'] }}">
                            <input type="hidden" name="role" value="{{ $filters['role'] }}">
                            <input type="hidden" name="status" value="{{ $filters['status'] }}">
                            <input type="hidden" name="gender" value="{{ $filters['gender'] }}">
                            <input type="hidden" name="verification" value="{{ $filters['verification'] }}">
                            <input type="hidden" name="sort" value="{{ $filters['sort'] }}">

                            <label for="footer-per-page" class="text-sm font-medium" style="color: var(--app-muted);">Show</label>
                            <select
                                id="footer-per-page"
                                name="per_page"
                                onchange="this.form.submit()"
                                class="min-w-[120px] rounded-xl border px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                            >
                                <option value="10" @selected($filters['per_page'] === '10')>10</option>
                                <option value="20" @selected($filters['per_page'] === '20')>20</option>
                                <option value="50" @selected($filters['per_page'] === '50')>50</option>
                                <option value="100" @selected($filters['per_page'] === '100')>100</option>
                                <option value="all" @selected($filters['per_page'] === 'all')>All</option>
                            </select>
                        </form>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
