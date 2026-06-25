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
        <p class="text-body-md text-on-surface-variant mb-md">No problem. Enter your email and we'll send you a reset link.</p>

        <x-auth-session-status class="mb-md" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-md">
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <div>
                <x-primary-button class="w-full justify-center">
                    {{ __('Send Reset Link') }}
                </x-primary-button>
            </div>
        </form>

        <p class="mt-md text-center text-sm text-on-surface-variant">
            <a href="{{ route('login') }}" wire:navigate class="text-primary hover:text-primary/80 font-medium transition-colors">← Back to login</a>
        </p>
    </div>
</div>
