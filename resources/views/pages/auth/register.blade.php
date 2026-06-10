<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-semibold text-zinc-900">Join FunShirt</h1>
            <p class="mt-1 text-sm text-zinc-600">Register an account to start creating custom t-shirts.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Full Legal Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        placeholder="John Doe"
                        required
                        autocomplete="name"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-zinc-700">Email Address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="john@example.com"
                        required
                        autocomplete="email"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-zinc-700">Secure Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-zinc-700">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="gender" class="mb-1 block text-sm font-medium text-zinc-700">Gender Identification</label>
                    <select id="gender" name="gender" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select</option>
                        <option value="M" @selected(old('gender') === 'M')>Male</option>
                        <option value="F" @selected(old('gender') === 'F')>Female</option>
                    </select>
                </div>
                <div>
                    <label for="nif" class="mb-1 block text-sm font-medium text-zinc-700">NIF (Tax ID - Optional)</label>
                    <input
                        id="nif"
                        name="nif"
                        type="text"
                        value="{{ old('nif') }}"
                        placeholder="123456789"
                        inputmode="numeric"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <div>
                <label for="address" class="mb-1 block text-sm font-medium text-zinc-700">Delivery Shipping Address (Optional)</label>
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                >{{ old('address') }}</textarea>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc ps-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Complete Registration
                </button>
                <p class="text-center text-sm text-zinc-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
