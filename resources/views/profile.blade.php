<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: var(--app-text);">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 rounded-3xl border px-6 py-6 shadow-sm sm:px-8" style="background: var(--app-surface); border-color: var(--app-border);">
                <div class="max-w-3xl space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em]" style="color: var(--app-muted);">Account Center</p>
                    <h1 class="text-3xl font-semibold leading-tight sm:text-4xl" style="color: var(--app-text);">Profile Settings</h1>
                    <p class="text-sm leading-6 sm:text-base" style="color: var(--app-muted);">
                        Update your personal account information here. Password and account removal stay in separate secured panels.
                    </p>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_minmax(320px,0.55fr)]">
                <div class="rounded-3xl border p-5 shadow-sm sm:p-8" style="background: var(--app-surface); border-color: var(--app-border);">
                    <div class="max-w-5xl">
                        <livewire:profile.profile />
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border p-5 shadow-sm sm:p-8" style="background: var(--app-surface); border-color: var(--app-border);">
                        <div class="max-w-xl">
                            <livewire:profile.update-password-form />
                        </div>
                    </div>

                    <div class="rounded-3xl border p-5 shadow-sm sm:p-8" style="background: var(--app-surface); border-color: var(--app-border);">
                        <div class="max-w-xl">
                            <livewire:profile.delete-user-form />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
