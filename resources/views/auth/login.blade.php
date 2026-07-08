<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                   class="auth-input @error('email') border-red-400 @enderror"
                   placeholder="you@company.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600" />
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="auth-input @error('password') border-red-400 @enderror"
                   placeholder="Enter your password">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-slate-600">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="auth-btn">
            Sign in
        </button>
    </form>
</x-guest-layout>
