<x-layouts::main-content title="Create Account" heading="Join FunShirt" subheading="Register an account to start creating custom t-shirts">
    <div class="max-w-xl mx-auto my-8 p-8 bg-zinc-50 border border-zinc-200 dark:bg-gray-900 rounded-xl shadow-sm">
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input name="name" label="Full Legal Name" required placeholder="John Doe" />
                <flux:input name="email" type="email" label="Email Address" required placeholder="john@example.com" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input name="password" type="password" label="Secure Password" required placeholder="••••••••" />
                <flux:input name="password_confirmation" type="password" label="Confirm Password" required placeholder="••••••••" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Gender Identification</label>
                    <select name="gender" class="w-full bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <flux:input name="nif" label="NIF (Tax ID - Optional)" placeholder="123456789" />
            </div>

            <flux:textarea name="address" label="Delivery Shipping Address (Optional)" placeholder="Street address, apartment, postal code..." rows="3" />

            <div class="pt-4">
                <flux:button type="submit" variant="primary" class="w-full justify-center">Complete Registration</flux:button>
            </div>
        </form>
    </div>
</x-layouts::main-content>