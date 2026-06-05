<x-layouts::main-content title="Sign In" heading="Welcome Back" subheading="Log in to check out your customized apparel profile">
    <div class="max-w-md mx-auto my-12 p-8 bg-zinc-50 border border-zinc-200 rounded-xl shadow-sm">
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            
            <flux:input name="email" type="email" label="Email Address" required autofocus placeholder="name@domain.com" />
            
            <flux:input name="password" type="password" label="Password" required viewable placeholder="••••••••" />
            
            <div class="flex items-center justify-between pt-2">
                <flux:checkbox name="remember" label="Keep me signed in" />
            </div>

            <div class="pt-4">
                <flux:button type="submit" variant="primary" class="w-full justify-center">Authenticate Session</flux:button>
            </div>
        </form>
    </div>
</x-layouts::main-content>