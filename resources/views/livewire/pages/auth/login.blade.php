<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="glass rounded-2xl p-lg shadow-xl shadow-black/5 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.25s;">
        <h2 class="text-headline-md font-bold text-on-surface mb-xs">Welcome back</h2>
        <p class="text-body-md text-on-surface-variant mb-lg">Sign in to your authenticator.</p>

        @if (session('status'))
            <div class="bg-secondary-container/20 border border-secondary-container/50 rounded-xl p-3 flex items-center gap-2 mb-md animate-slide-up">
                <span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
                <p class="text-label-sm text-secondary font-medium">{{ session('status') }}</p>
            </div>
        @endif

        <form wire:submit="login" class="space-y-md">
            <div>
                <label for="email" class="block text-label-sm font-label-sm text-on-surface mb-xs">Email</label>
                <input wire:model="form.email" id="email" name="email" type="email" required autofocus autocomplete="username"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="you@example.com">
                @error('form.email') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-label-sm font-label-sm text-on-surface mb-xs">Password</label>
                <input wire:model="form.password" id="password" name="password" type="password" required autocomplete="current-password"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="••••••••">
                @error('form.password') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label for="remember" class="inline-flex items-center gap-2 cursor-pointer group">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-outline-variant text-primary shadow-sm focus:ring-primary w-4 h-4" name="remember">
                    <span class="text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-primary hover:text-primary/80 font-medium transition-colors" href="{{ route('password.request') }}" wire:navigate>
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-md py-sm bg-primary text-on-primary border border-transparent rounded-xl font-label-sm text-label-sm tracking-wide btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25">
                Log in
            </button>
        </form>

        <p class="mt-md text-center text-sm text-on-surface-variant">
            Don't have an account?
            <a href="{{ route('register') }}" wire:navigate class="text-primary hover:text-primary/80 font-medium transition-colors">Register</a>
        </p>
    </div>
</div>
