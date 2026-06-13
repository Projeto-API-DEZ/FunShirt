<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public ?string $nif = null;
    public ?string $address = null;
    public ?string $default_payment_type = null;
    public ?string $default_payment_ref = null;

    #[Validate(['nullable', 'image', 'max:2048'])]
    public $photo_file = null;

    public function mount(): void
    {
        $user = Auth::user()->load('customer');

        $this->name = $user->name;
        $this->email = $user->email;
        $this->nif = $user->customer?->nif;
        $this->address = $user->customer?->address;
        $this->default_payment_type = $user->customer?->default_payment_type;
        $this->default_payment_ref = $user->customer?->default_payment_ref;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'photo_file' => ['nullable', 'image', 'max:2048'],
        ];

        if ($user->isCustomer()) {
            $rules['nif'] = ['nullable', 'digits:9'];
            $rules['address'] = ['nullable', 'string', 'max:1000'];
            $rules['default_payment_type'] = ['nullable', Rule::in(['Visa', 'PayPal', 'MB WAY'])];
            $rules['default_payment_ref'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $this->validate($rules);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($this->photo_file) {
            if ($user->hasUploadedPhoto()) {
                Storage::disk('public')->delete($user->normalizedPhotoPath());
            }

            $user->photo_url = $this->photo_file->store('photos', 'public');
        }

        $user->save();

        if ($user->isCustomer()) {
            $user->customer()->updateOrCreate(
                ['id' => $user->id],
                [
                    'nif' => $validated['nif'] ?: null,
                    'address' => $validated['address'] ?: null,
                    'default_payment_type' => $validated['default_payment_type'] ?: null,
                    'default_payment_ref' => $validated['default_payment_ref'] ?: null,
                ]
            );
        }

        $this->photo_file = null;

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function deletePhoto(): void
    {
        $user = Auth::user();

        if ($user->hasUploadedPhoto()) {
            Storage::disk('public')->delete($user->normalizedPhotoPath());
            $user->forceFill(['photo_url' => null])->save();
        }

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

@php($profileUser = auth()->user())
@php($showBillingPreferences = $profileUser?->isCustomer())

<section>
    <header>
        <h2 class="text-xl font-semibold" style="color: var(--app-text);">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-sm leading-6" style="color: var(--app-muted);">
            {{ __("Update your account information, avatar and personal settings.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-8 space-y-6">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_300px]">
            <div class="space-y-6">
                <div class="rounded-3xl border p-6 sm:p-7" style="background: var(--app-surface-2); border-color: var(--app-border);">
                    <div class="mb-5">
                        <h3 class="text-base font-semibold" style="color: var(--app-text);">Account Details</h3>
                        <p class="mt-1 text-sm" style="color: var(--app-muted);">Core account information used for identity and authentication.</p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($profileUser instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $profileUser->hasVerifiedEmail())
                                <div>
                                    <p class="mt-2 text-sm" style="color: var(--app-text);">
                                        {{ __('Your email address is unverified.') }}

                                        <button wire:click.prevent="sendVerification" class="rounded-md text-sm underline underline-offset-4 transition hover:opacity-80" style="color: var(--app-accent);">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 text-sm font-medium text-green-600">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($showBillingPreferences)
                    <div class="rounded-3xl border p-6 sm:p-7" style="background: var(--app-surface-2); border-color: var(--app-border);">
                        <div class="mb-5">
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-base font-semibold" style="color: var(--app-text);">Billing &amp; Payment Preferences</h3>
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium" style="background: var(--app-surface); color: var(--app-muted);">Optional</span>
                            </div>
                            <p class="mt-2 text-sm" style="color: var(--app-muted);">Optional checkout information used to speed up future customer orders.</p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="nif" class="block text-sm font-medium" style="color: var(--app-text);">Tax Number (NIF)</label>
                                <input wire:model="nif" id="nif" name="nif" type="text" inputmode="numeric" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <x-input-error class="mt-2" :messages="$errors->get('nif')" />
                            </div>

                            <div>
                                <label for="default_payment_type" class="block text-sm font-medium" style="color: var(--app-text);">Default Payment Method</label>
                                <select wire:model="default_payment_type" id="default_payment_type" name="default_payment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select a payment method</option>
                                    <option value="Visa">Visa</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="MB WAY">MB WAY</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('default_payment_type')" />
                            </div>
                        </div>

                        <div class="mt-5 grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="default_payment_ref" class="block text-sm font-medium" style="color: var(--app-text);">Preferred Payment Reference</label>
                                <input wire:model="default_payment_ref" id="default_payment_ref" name="default_payment_ref" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <x-input-error class="mt-2" :messages="$errors->get('default_payment_ref')" />
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium" style="color: var(--app-text);">Address</label>
                                <textarea wire:model="address" id="address" name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                    <x-action-message class="me-3" on="profile-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-3xl border p-6 sm:p-7" style="background: var(--app-surface); border-color: var(--app-border);">
                    <div class="flex flex-col items-center text-center">
                        <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-indigo-600 text-2xl font-semibold text-white shadow-sm">
                            @if ($profileUser->hasUploadedPhoto())
                                <img
                                    src="{{ $profileUser->photoFullUrl }}"
                                    alt=""
                                    class="h-full w-full object-cover"
                                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                >
                                <span class="hidden">{{ $profileUser->initials() }}</span>
                            @else
                                {{ $profileUser->initials() }}
                            @endif
                        </div>

                        <div class="mt-4 space-y-1">
                            <h3 class="text-base font-semibold" style="color: var(--app-text);">{{ $profileUser->name }}</h3>
                            <p class="text-sm" style="color: var(--app-muted);">{{ $profileUser->email }}</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div>
                            <label for="photo_file" class="block text-sm font-medium" style="color: var(--app-text);">Profile Photo</label>
                            <p class="mt-1 text-sm" style="color: var(--app-muted);">Upload a photo or keep the initials-based avatar.</p>
                            <input wire:model="photo_file" id="photo_file" type="file" accept="image/*" class="mt-2 block w-full text-sm text-gray-600 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:font-medium file:text-white hover:file:bg-indigo-500">
                            <x-input-error class="mt-2" :messages="$errors->get('photo_file')" />
                        </div>

                        <div class="flex flex-wrap justify-center gap-3">
                            @if ($profileUser->hasUploadedPhoto())
                                <button type="button" wire:click="deletePhoto" class="inline-flex items-center rounded-full border px-4 py-2 text-sm font-medium transition" style="border-color: var(--app-border); background: var(--app-surface); color: var(--app-text);">
                                    Delete Photo
                                </button>
                            @endif

                            @if ($photo_file)
                                <span class="inline-flex items-center rounded-full px-3 py-2 text-xs font-medium" style="background: var(--app-surface-2); color: var(--app-muted);">
                                    New image ready to save
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</section>
