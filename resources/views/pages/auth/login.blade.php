<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-semibold text-zinc-900">Welcome Back</h1>
            <p class="mt-1 text-sm text-zinc-600">Log in to access your FunShirt account.</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-zinc-700">Email Address</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="name@domain.com"
                    required
                    autofocus
                    autocomplete="email"
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-medium text-zinc-700">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <div class="flex items-center justify-between pt-1">
                <label for="remember" class="inline-flex items-center gap-2 text-sm text-zinc-700">
                    <input
                        id="remember"
                        name="remember"
                        type="checkbox"
                        value="1"
                        class="h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <span>Keep me signed in</span>
                </label>

                <a
                    href="{{ route('password.request') }}"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                >
                    Forgot password?
                </a>
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
                    Authenticate Session
                </button>
                <p class="text-center text-sm text-zinc-600">
                    Need an account?
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Register</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
