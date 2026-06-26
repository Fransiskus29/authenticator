<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        Session::flash('status', __($status));
        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <div class="glass rounded-2xl p-lg shadow-xl shadow-black/5 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.25s;">
        <h2 class="text-headline-md font-bold text-on-surface mb-xs">Reset password</h2>
        <p class="text-body-md text-on-surface-variant mb-lg">Enter your new password below.</p>

        <form wire:submit="resetPassword" class="space-y-md">
            <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                <label for="email" class="block text-label-sm font-label-sm text-on-surface mb-xs">Email</label>
                <input wire:model="email" id="email" type="email" name="email" required autofocus autocomplete="username"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="you@example.com">
                @error('email') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="animate-fade-in-up" style="animation-delay: 0.35s;">
                <label for="password" class="block text-label-sm font-label-sm text-on-surface mb-xs">Password</label>
                <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="Min 8 characters">
                @error('password') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="animate-fade-in-up" style="animation-delay: 0.4s;">
                <label for="password_confirmation" class="block text-label-sm font-label-sm text-on-surface mb-xs">Confirm Password</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="Repeat password">
                @error('password_confirmation') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="animate-fade-in-up pt-xs" style="animation-delay: 0.45s;">
                <button type="submit" class="w-full inline-flex items-center justify-center px-md py-sm bg-primary text-on-primary border border-transparent rounded-xl font-label-sm text-label-sm tracking-wide btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
