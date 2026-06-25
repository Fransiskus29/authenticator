<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        event(new Registered($user = User::create($validated)));
        Auth::login($user);
        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="glass rounded-2xl p-lg shadow-xl shadow-black/5 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.25s;">
        <h2 class="text-headline-md font-bold text-on-surface mb-xs">Create account</h2>
        <p class="text-body-md text-on-surface-variant mb-lg">Get started with your authenticator.</p>

        <form wire:submit="register" class="space-y-md">
            <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                <label for="name" class="block text-label-sm font-label-sm text-on-surface mb-xs">Name</label>
                <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="Your name">
                <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
            </div>

            <div class="animate-fade-in-up" style="animation-delay: 0.35s;">
                <label for="email" class="block text-label-sm font-label-sm text-on-surface mb-xs">Email</label>
                <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="you@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <div class="animate-fade-in-up" style="animation-delay: 0.4s;">
                <label for="password" class="block text-label-sm font-label-sm text-on-surface mb-xs">Password</label>
                <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="Min 8 characters">
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <div class="animate-fade-in-up" style="animation-delay: 0.45s;">
                <label for="password_confirmation" class="block text-label-sm font-label-sm text-on-surface mb-xs">Confirm Password</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="Repeat password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
            </div>

            <div class="animate-fade-in-up pt-xs" style="animation-delay: 0.5s;">
                <button type="submit" class="w-full inline-flex items-center justify-center px-md py-sm bg-primary text-on-primary border border-transparent rounded-xl font-label-sm text-label-sm tracking-wide btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25">
                    Create account
                </button>
            </div>
        </form>

        <p class="mt-md text-center text-sm text-on-surface-variant">
            Already have an account?
            <a href="{{ route('login') }}" wire:navigate class="text-primary hover:text-primary/80 font-medium transition-colors">Log in</a>
        </p>
    </div>
</div>
