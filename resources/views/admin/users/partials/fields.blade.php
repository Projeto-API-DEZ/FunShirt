@php
    $mode = $mode ?? 'edit';
    $readonly = $mode === 'show';
    $user = $user ?? new \App\Models\User();
    $customer = $user->customer;
    $roleLabels = [
        'C' => 'Customer',
        'F' => 'Staff',
        'A' => 'Admin',
    ];

    $verificationLabel = $user->exists
        ? ($user->email_verified_at ? 'Verified' : 'Pending verification')
        : 'Will be pending until email verification';

    $blockedLabel = $user->blocked ? 'Blocked' : 'Active';
    $avatarSize = $mode === 'show' ? 250 : 160;
@endphp

<div class="grid gap-10 xl:grid-cols-[1.25fr_0.75fr]">
    <div class="space-y-10">
        <section class="rounded-2xl border p-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
            <div class="mb-5">
                <h3 class="text-base font-semibold" style="color: var(--app-text);">Identity</h3>
                <p class="mt-1 text-sm" style="color: var(--app-muted);">Core account data used for access and role assignment.</p>
            </div>

            <div class="grid md:grid-cols-2" style="column-gap: 15px; row-gap: 15px;">
                <div>
                    <label for="name" class="block text-sm font-medium" style="color: var(--app-text);">Full Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        @disabled($readonly)
                        placeholder="Example: Maria Silva"
                        class="mt-1 block w-full rounded-xl border px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium" style="color: var(--app-text);">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        @disabled($readonly)
                        placeholder="name@example.com"
                        class="mt-1 block w-full rounded-xl border px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                    >
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium" style="color: var(--app-text);">Gender</label>
                    <select
                        id="gender"
                        name="gender"
                        @disabled($readonly)
                        class="mt-1 block w-full rounded-xl border px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                    >
                        <option value="M" @selected(old('gender', $user->gender) === 'M')>Male</option>
                        <option value="F" @selected(old('gender', $user->gender) === 'F')>Female</option>
                    </select>
                </div>

                <div>
                    <label for="user_type" class="block text-sm font-medium" style="color: var(--app-text);">Role</label>
                    <select
                        id="user_type"
                        name="user_type"
                        @disabled($readonly)
                        class="mt-1 block w-full rounded-xl border px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                    >
                        <option value="C" @selected(old('user_type', $user->user_type) === 'C')>Customer</option>
                        <option value="F" @selected(old('user_type', $user->user_type) === 'F')>Staff</option>
                        <option value="A" @selected(old('user_type', $user->user_type) === 'A')>Admin</option>
                    </select>
                </div>

                @if ($mode === 'create')
                    <div>
                        <label for="password" class="block text-sm font-medium" style="color: var(--app-text);">Password</label>
                        <div class="relative mt-1">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="block w-full rounded-xl border px-3 py-2 pe-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                            >
                            <button
                                type="button"
                                data-password-toggle
                                data-target="password"
                                aria-label="Toggle password visibility"
                                aria-pressed="false"
                                class="absolute right-3 top-1/2 inline-flex h-5 w-5 -translate-y-1/2 items-center justify-center"
                                style="color: var(--app-muted);"
                            >
                                <svg data-eye-open xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="hidden h-5 w-5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m3 3 18 18" />
                                    <path d="M10.584 10.587a2 2 0 0 0 2.829 2.828" />
                                    <path d="M9.363 5.365A10.74 10.74 0 0 1 12 5c4.552 0 8.455 2.842 10 7a10.717 10.717 0 0 1-4.211 5.145" />
                                    <path d="M6.228 6.228A10.723 10.723 0 0 0 2 12c1.545 4.158 5.448 7 10 7a10.72 10.72 0 0 0 5.772-1.228" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium" style="color: var(--app-text);">Confirm Password</label>
                        <div class="relative mt-1">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                class="block w-full rounded-xl border px-3 py-2 pe-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                            >
                            <button
                                type="button"
                                data-password-toggle
                                data-target="password_confirmation"
                                aria-label="Toggle password visibility"
                                aria-pressed="false"
                                class="absolute right-3 top-1/2 inline-flex h-5 w-5 -translate-y-1/2 items-center justify-center"
                                style="color: var(--app-muted);"
                            >
                                <svg data-eye-open xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="hidden h-5 w-5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m3 3 18 18" />
                                    <path d="M10.584 10.587a2 2 0 0 0 2.829 2.828" />
                                    <path d="M9.363 5.365A10.74 10.74 0 0 1 12 5c4.552 0 8.455 2.842 10 7a10.717 10.717 0 0 1-4.211 5.145" />
                                    <path d="M6.228 6.228A10.723 10.723 0 0 0 2 12c1.545 4.158 5.448 7 10 7a10.72 10.72 0 0 0 5.772-1.228" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section class="rounded-2xl border p-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
            <div class="mb-5">
                <h3 class="text-base font-semibold" style="color: var(--app-text);">Billing & Payment Details</h3>
                <p class="mt-1 text-sm" style="color: var(--app-muted);">
                    Optional customer details stored in the `customers` table when available.
                </p>
            </div>

            <div class="grid md:grid-cols-2" style="column-gap: 15px; row-gap: 15px;">
                <div>
                    <label for="nif" class="block text-sm font-medium" style="color: var(--app-text);">NIF</label>
                    <input
                        id="nif"
                        name="nif"
                        type="text"
                        value="{{ old('nif', $customer?->nif) }}"
                        @disabled($readonly)
                        placeholder="123456789"
                        class="mt-1 block w-full rounded-xl border px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                    >
                </div>

                <div>
                    <label for="default_payment_type" class="block text-sm font-medium" style="color: var(--app-text);">Default Payment Type</label>
                    <select
                        id="default_payment_type"
                        name="default_payment_type"
                        @disabled($readonly)
                        class="mt-1 block w-full rounded-xl border px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                    >
                        <option value="">Select a payment method</option>
                        <option value="Visa" @selected(old('default_payment_type', $customer?->default_payment_type) === 'Visa')>Visa</option>
                        <option value="PayPal" @selected(old('default_payment_type', $customer?->default_payment_type) === 'PayPal')>PayPal</option>
                        <option value="MB WAY" @selected(old('default_payment_type', $customer?->default_payment_type) === 'MB WAY')>MB WAY</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium" style="color: var(--app-text);">Address</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        @disabled($readonly)
                        placeholder="Street, city and postal code"
                        class="mt-1 block w-full rounded-xl border px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                    >{{ old('address', $customer?->address) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="default_payment_ref" class="block text-sm font-medium" style="color: var(--app-text);">Default Payment Reference</label>
                    <input
                        id="default_payment_ref"
                        name="default_payment_ref"
                        type="text"
                        value="{{ old('default_payment_ref', $customer?->default_payment_ref) }}"
                        @disabled($readonly)
                        placeholder="Card number, email or phone"
                        class="mt-1 block w-full rounded-xl border px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        style="background: var(--app-surface); border-color: var(--app-border); color: var(--app-text);"
                    >
                </div>
            </div>
        </section>
    </div>

    <div class="space-y-10">
        <section class="rounded-2xl border p-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
            <div class="mb-5">
                <h3 class="text-base font-semibold" style="color: var(--app-text);">Avatar</h3>
                <p class="mt-1 text-sm" style="color: var(--app-muted);">
                    {{ $readonly ? 'Read-only preview for the selected account.' : 'Upload an avatar or keep the initials-based fallback.' }}
                </p>
            </div>

            <div class="flex flex-col items-center gap-4">
                <div class="flex items-center justify-center overflow-hidden rounded-full border font-semibold uppercase shadow-sm"
                     style="width: {{ $avatarSize }}px; height: {{ $avatarSize }}px; min-width: {{ $avatarSize }}px; min-height: {{ $avatarSize }}px; max-width: {{ $avatarSize }}px; max-height: {{ $avatarSize }}px; border-color: var(--app-border); background: {{ $user->hasUploadedPhoto() ? 'transparent' : '#4f46e5' }}; color: {{ $user->hasUploadedPhoto() ? 'transparent' : '#ffffff' }}; font-size: {{ $mode === 'show' ? '72px' : '42px' }};">
                    @if ($user->hasUploadedPhoto())
                        <img
                            src="{{ $user->photoFullUrl }}"
                            alt=""
                            class="block object-cover object-center"
                            style="width: {{ $avatarSize }}px; height: {{ $avatarSize }}px; min-width: {{ $avatarSize }}px; min-height: {{ $avatarSize }}px; max-width: {{ $avatarSize }}px; max-height: {{ $avatarSize }}px;"
                        >
                    @elseif ($user->exists && filled($user->name))
                        {{ $user->initials() }}
                    @else
                        NU
                    @endif
                </div>

                @if (! $readonly)
                    <div class="w-full">
                        <label for="photo_file" class="block text-sm font-medium" style="color: var(--app-text);">Photo</label>
                        <input
                            id="photo_file"
                            name="photo_file"
                            type="file"
                            class="mt-1 block w-full text-sm"
                            style="color: var(--app-muted);"
                        >
                    </div>
                @endif
            </div>
        </section>

        @if ($mode !== 'create')
            <section class="rounded-2xl border p-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                <div class="mb-5">
                    <h3 class="text-base font-semibold" style="color: var(--app-text);">Account Status</h3>
                    @if ($mode === 'edit')
                        <p class="mt-1 text-sm" style="color: var(--app-muted);">
                            Review verification, creation date and blocking status before saving changes.
                        </p>
                    @endif
                </div>

                <div style="display: grid; row-gap: 15px;">
                    <div>
                        <label class="block text-sm font-medium" style="color: var(--app-text);">Email Verification</label>
                        <div class="mt-1 rounded-xl border px-3 py-2 text-sm" style="background: var(--app-surface-2); border-color: var(--app-border); color: var(--app-text);">
                            {{ $verificationLabel }}
                        </div>
                    </div>

                    @if ($mode === 'edit')
                        <div>
                            <label class="block text-sm font-medium" style="color: var(--app-text);">Current Role</label>
                            <div class="mt-1 rounded-xl border px-3 py-2 text-sm" style="background: var(--app-surface-2); border-color: var(--app-border); color: var(--app-text);">
                                {{ $roleLabels[$user->user_type] ?? 'Not set' }}
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium" style="color: var(--app-text);">Created Date</label>
                            <div class="mt-1 rounded-xl border px-3 py-2 text-sm" style="background: var(--app-surface-2); border-color: var(--app-border); color: var(--app-text);">
                                {{ optional($user->created_at)->format('Y-m-d H:i') ?: '-' }}
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium" style="color: var(--app-text);">Blocked</label>
                        <div class="mt-1 rounded-xl border px-3 py-2 text-sm" style="background: var(--app-surface-2); border-color: var(--app-border); color: var(--app-text);">
                            {{ $blockedLabel }}
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($mode === 'edit')
            <section class="rounded-2xl border p-6 shadow-sm" style="background: var(--app-surface); border-color: var(--app-border);">
                <div class="mb-5">
                    <h3 class="text-base font-semibold" style="color: var(--app-text);">Administrative Actions</h3>
                    <p class="mt-1 text-sm" style="color: var(--app-muted);">
                        Only the edit screen can block or unblock an account.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button
                        type="submit"
                        form="toggle-block-form"
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium"
                        style="background: {{ $user->blocked ? '#ecfdf5' : '#fef2f2' }}; color: {{ $user->blocked ? '#047857' : '#b91c1c' }};"
                    >
                        {{ $user->blocked ? 'Unblock User' : 'Block User' }}
                    </button>
                </div>
            </section>
        @endif
    </div>
</div>
