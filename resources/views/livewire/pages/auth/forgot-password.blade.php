<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');
        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="glass rounded-2xl p-lg shadow-xl shadow-black/5 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.25s;">
        <h2 class="text-headline-md font-bold text-on-surface mb-xs">Forgot password?</h2>
        <p class="text-body-md text-on-surface-variant mb-lg">No problem. Enter your email and we'll send you a reset link.</p>

        @if (session('status'))
            <div class="bg-secondary-container/20 border border-secondary-container/50 rounded-xl p-3 flex items-center gap-2 mb-md animate-slide-up">
                <span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
                <p class="text-label-sm text-secondary font-medium">{{ session('status') }}</p>
            </div>
        @endif

        <form wire:submit="sendPasswordResetLink" class="space-y-md">
            <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                <label for="email" class="block text-label-sm font-label-sm text-on-surface mb-xs">Email</label>
                <input wire:model="email" id="email" type="email" name="email" required autofocus autocomplete="username"
                       class="w-full bg-surface-container-lowest/80 border border-outline-variant rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                       placeholder="you@example.com">
                @error('email') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="animate-fade-in-up pt-xs" style="animation-delay: 0.35s;">
                <button type="submit" class="w-full inline-flex items-center justify-center px-md py-sm bg-primary text-on-primary border border-transparent rounded-xl font-label-sm text-label-sm tracking-wide btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25">
                    Send Reset Link
                </button>
            </div>
        </form>

        <p class="mt-md text-center text-sm text-on-surface-variant">
            <a href="{{ route('login') }}" wire:navigate class="text-primary hover:text-primary/80 font-medium transition-colors">← Back to login</a>
        </p>
    </div>
</div>
