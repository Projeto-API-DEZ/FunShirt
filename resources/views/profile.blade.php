<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
                <div class="rounded-2xl bg-white p-4 shadow sm:p-8">
                    <div class="max-w-4xl">
                        <livewire:profile.profile />
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl bg-white p-4 shadow sm:p-8">
                        <div class="max-w-xl">
                            <livewire:profile.update-password-form />
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white p-4 shadow sm:p-8">
                        <div class="max-w-xl">
                            <livewire:profile.delete-user-form />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
