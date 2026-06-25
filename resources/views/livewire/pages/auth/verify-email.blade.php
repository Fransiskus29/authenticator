<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="glass rounded-2xl p-lg shadow-xl shadow-black/5 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.25s;">
        <h2 class="text-headline-md font-bold text-on-surface mb-xs">Verify your email</h2>
        <p class="text-body-md text-on-surface-variant mb-md">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}</p>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-secondary-container/20 border border-secondary-container/50 rounded-xl p-3 flex items-center gap-2 mb-md animate-slide-up">
                <span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
                <p class="text-label-sm text-secondary font-medium">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</p>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <x-primary-button wire:click="sendVerification">
                {{ __('Resend Verification Email') }}
            </x-primary-button>
            <button wire:click="logout" type="submit" class="text-sm text-on-surface-variant hover:text-on-surface rounded-xl px-3 py-2 transition-colors btn-press">
                {{ __('Log Out') }}
            </button>
        </div>
    </div>
</div>
