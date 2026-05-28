<x-layouts::main-content title="Edit Profile" heading="Account Settings" subheading="Manage your personal details and shipping preferences">
    <div class="max-w-2xl mx-auto py-8">
        <div class="p-8 bg-zinc-50 border border-zinc-200 dark:bg-gray-900 rounded-xl shadow-sm">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input name="name" label="Full Name" value="{{ old('name', $user->name) }}" required />
                    <flux:input name="email" type="email" label="Email Address" value="{{ old('email', $user->email) }}" required />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Gender</label>
                        <select name="gender" class="w-full bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg p-2 text-sm">
                            <option value="M" {{ $user->gender === 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ $user->gender === 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                @if($user->isCustomer())
                    <div class="space-y-4 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                        <flux:input name="nif" label="NIF (Tax ID)" value="{{ old('nif', $user->customer?->nif) }}" />
                        <flux:textarea name="address" label="Default Shipping Address" rows="3">{{ old('address', $user->customer?->address) }}</flux:textarea>
                    </div>
                @endif

                <div class="pt-4">
                    <flux:button type="submit" variant="primary" class="w-full justify-center">Save Profile Updates</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::main-content>