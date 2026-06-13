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
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 pe-16 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >
                    <button
                        type="button"
                        data-password-toggle
                        data-target="password"
                        aria-label="Toggle password visibility"
                        aria-pressed="false"
                        class="absolute right-3 top-1/2 inline-flex h-5 w-5 -translate-y-1/2 items-center justify-center text-zinc-500 transition hover:text-indigo-600"
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

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);

                if (!input) {
                    return;
                }

                const showPassword = input.type === 'password';
                input.type = showPassword ? 'text' : 'password';
                button.setAttribute('aria-pressed', showPassword ? 'true' : 'false');
                button.querySelector('[data-eye-open]')?.classList.toggle('hidden', showPassword);
                button.querySelector('[data-eye-closed]')?.classList.toggle('hidden', !showPassword);
            });
        });
    </script>
</x-guest-layout>
