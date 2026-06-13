<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-semibold text-zinc-900">Verify Your Email</h1>
            <p class="text-sm text-zinc-500">
                We sent a verification link to your email address. Open that email and click the link before continuing.
            </p>
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                A new verification email has been sent successfully.
            </div>
        @endif

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="space-y-3 text-sm text-zinc-600">
                <p><span class="font-medium text-zinc-900">Signed in as:</span> {{ auth()->user()?->email }}</p>
                <p>If the first email does not arrive, you can request a new one below.</p>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <form method="POST" action="{{ route('email.verify.send') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                        Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
