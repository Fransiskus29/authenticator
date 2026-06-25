<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SecureAuth — TOTP authenticator for managing your two-factor authentication codes.">
    <meta name="theme-color" content="#f8f9ff">

    <title>{{ config('app.name', 'SecureAuth') }}</title>

    <script>
        (function() {
            var t = localStorage.getItem('theme');
            var d = (!t && window.matchMedia('(prefers-color-scheme: dark)').matches) || t === 'dark';
            if (d) document.documentElement.classList.add('dark');
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .hero-bg {
            background: linear-gradient(135deg, #e2dfff 0%, #f8f9ff 40%, #d3e4fe 70%, #e8e4ff 100%);
            position: relative;
            overflow: hidden;
        }
        .dark .hero-bg {
            background: linear-gradient(135deg, #1a1535 0%, #0f1115 40%, #151a25 70%, #1a1230 100%);
        }
        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(var(--color-primary), 0.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(var(--color-secondary), 0.06) 0%, transparent 50%);
        }
        .dark .hero-bg::before {
            background: radial-gradient(ellipse at 20% 50%, rgba(var(--color-primary), 0.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(var(--color-secondary), 0.08) 0%, transparent 50%);
        }
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.5;
            pointer-events: none;
        }
        .dark .orb { opacity: 0.3; }
        .orb-1 { width: 300px; height: 300px; background: rgba(var(--color-primary), 0.15); top: 10%; left: -5%; animation: orb-drift 25s ease-in-out infinite; }
        .orb-2 { width: 200px; height: 200px; background: rgba(var(--color-secondary), 0.12); top: 60%; right: 5%; animation: orb-drift 20s ease-in-out infinite reverse; }
        .orb-3 { width: 150px; height: 150px; background: rgba(var(--color-tertiary), 0.08); bottom: 10%; left: 30%; animation: orb-drift 30s ease-in-out infinite 5s; }

        @keyframes counter { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .code-row { animation: fade-in-up 0.5s ease-out forwards; opacity: 0; }
        .code-row:nth-child(1) { animation-delay: 0.2s; }
        .code-row:nth-child(2) { animation-delay: 0.35s; }
        .code-row:nth-child(3) { animation-delay: 0.5s; }
    </style>
</head>
<body class="bg-background text-on-background font-sans text-body-md min-h-screen antialiased">

    {{-- Nav --}}
    <nav class="fixed top-0 inset-x-0 z-50 glass border-b border-outline-variant/30">
        <div class="max-w-container-max mx-auto px-md flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-xs group" wire:navigate>
                <span class="material-symbols-outlined text-primary text-[28px] font-bold transition-transform duration-300 group-hover:scale-110" style="font-variation-settings: 'FILL' 1;">shield</span>
                <span class="font-sans text-headline-md font-bold text-primary tracking-tight">SecureAuth</span>
            </a>
            <div class="flex items-center gap-xs">
                <button onclick="toggleTheme()" class="theme-toggle text-on-surface-variant hover:bg-surface-container-low/80 rounded-full p-2 transition-all duration-300 hover:scale-105" title="Toggle dark mode">
                    <span class="material-symbols-outlined">dark_mode</span>
                </button>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" wire:navigate class="text-label-sm text-on-surface-variant hover:text-primary transition-all duration-300 px-sm py-2 rounded-lg hover:bg-surface-container-low/50">Log in</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" wire:navigate class="text-label-sm text-on-primary bg-primary hover:opacity-90 transition-all duration-300 px-md py-2 rounded-lg btn-press hover:shadow-lg hover:shadow-primary/20">Get started</a>
                @endif
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero-bg pt-32 pb-20 px-md sm:pt-40 sm:pb-28 relative">
        <div class="absolute inset-0 dot-grid pointer-events-none"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <div class="max-w-container-max mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-xs bg-primary-fixed/60 text-on-primary-fixed text-label-sm font-medium px-sm py-1.5 rounded-full mb-md animate-fade-in-up" style="animation-delay: 0.1s;">
                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                TOTP Authenticator
            </div>

            <h1 class="text-headline-lg sm:text-[clamp(2.5rem,5vw,4rem)] sm:leading-[clamp(3rem,6vw,4.75rem)] font-bold text-on-background max-w-3xl mx-auto animate-fade-in-up" style="text-wrap: balance; animation-delay: 0.2s;">
                Your 2FA codes,<br class="hidden sm:block"> one secure place.
            </h1>

            <p class="mt-md text-body-md sm:text-lg text-on-surface-variant max-w-xl mx-auto animate-fade-in-up" style="text-wrap: balance; animation-delay: 0.3s;">
                Generate time-based one-time passwords for all your accounts. No cloud sync, no vendor lock-in — your secrets stay on your device.
            </p>

            <div class="mt-lg flex flex-col sm:flex-row items-center justify-center gap-sm animate-fade-in-up" style="animation-delay: 0.4s;">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" wire:navigate class="w-full sm:w-auto inline-flex items-center justify-center gap-xs px-lg py-sm bg-primary text-on-primary rounded-lg font-label-sm text-label-sm tracking-wide btn-press hover:shadow-xl hover:shadow-primary/25 transition-all duration-300">
                        <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                        Start for free
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" wire:navigate class="w-full sm:w-auto inline-flex items-center justify-center gap-xs px-lg py-sm bg-surface-container-low/80 text-on-surface border border-outline-variant rounded-lg font-label-sm text-label-sm tracking-wide btn-press hover:bg-surface-container hover:border-primary/30 transition-all duration-300">
                        I already have an account
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-20 px-md relative">
        <div class="absolute inset-0 dot-grid pointer-events-none"></div>
        <div class="max-w-container-max mx-auto relative z-[1]">
            <div class="text-center mb-xl reveal">
                <h2 class="text-headline-md sm:text-headline-lg font-bold text-on-background" style="text-wrap: balance;">Everything you need,<br class="hidden sm:block"> nothing you don't.</h2>
                <p class="mt-sm text-body-md text-on-surface-variant max-w-lg mx-auto">Built for people who take security seriously but don't want the friction.</p>
            </div>

            <div class="grid gap-md sm:grid-cols-2 lg:grid-cols-3 stagger-in">
                @php
                    $features = [
                        ['icon' => 'pin', 'color' => 'primary', 'title' => 'Live TOTP Codes', 'desc' => 'Real-time code generation with visible countdown. Your codes refresh every 30 seconds, always in sync.'],
                        ['icon' => 'search', 'color' => 'secondary', 'title' => 'Instant Search', 'desc' => 'Find any account in milliseconds. Search by name or issuer — no scrolling through endless lists.'],
                        ['icon' => 'lock', 'color' => 'tertiary', 'title' => 'Encrypted Export', 'desc' => 'Export your accounts encrypted. Move between devices without exposing your secrets in plaintext.'],
                        ['icon' => 'qr_code_scanner', 'color' => 'primary', 'title' => 'Easy Setup', 'desc' => 'Add accounts with a label and secret key. Works with any service that supports TOTP — Google, GitHub, Slack, and more.'],
                        ['icon' => 'download', 'color' => 'secondary', 'title' => 'Import Accounts', 'desc' => 'Migrating from another authenticator? Import your existing accounts in one step.'],
                        ['icon' => 'phonelink_off', 'color' => 'tertiary', 'title' => 'No Cloud Dependency', 'desc' => 'Your data lives on this server, under your control. No third-party sync, no surprise lockouts.'],
                    ];
                @endphp
                @foreach ($features as $f)
                    <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-md sm:p-lg glow-hover transition-all duration-300 group">
                        <div class="w-11 h-11 rounded-xl bg-{{ $f['color'] }}-container flex items-center justify-center mb-sm transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                            <span class="material-symbols-outlined text-on-{{ $f['color'] }}-container text-[22px]">{{ $f['icon'] }}</span>
                        </div>
                        <h3 class="text-body-md font-semibold text-on-surface mb-xs">{{ $f['title'] }}</h3>
                        <p class="text-body-md text-on-surface-variant leading-relaxed" style="text-wrap: pretty;">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Code preview --}}
    <section class="py-20 px-md bg-surface-container-low/50">
        <div class="max-w-container-max mx-auto">
            <div class="grid lg:grid-cols-2 gap-xl items-center">
                <div class="reveal">
                    <h2 class="text-headline-md sm:text-headline-lg font-bold text-on-background" style="text-wrap: balance;">Codes at a glance.</h2>
                    <p class="mt-sm text-body-md text-on-surface-variant max-w-md" style="text-wrap: pretty;">See all your OTP codes on one screen. The countdown timer shows exactly how long each code stays valid — no guessing, no rushing.</p>
                    <div class="mt-md space-y-xs">
                        @foreach (['30-second refresh cycle', 'Standard TOTP (RFC 6238)', 'Works with any TOTP provider'] as $check)
                            <div class="flex items-center gap-sm text-label-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span>
                                {{ $check }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="reveal glass rounded-2xl p-md font-mono shadow-xl shadow-black/5 dark:shadow-black/20">
                    <div class="flex items-center gap-xs mb-sm">
                        <div class="w-3 h-3 rounded-full bg-tertiary/60"></div>
                        <div class="w-3 h-3 rounded-full bg-[#e8a317]/60"></div>
                        <div class="w-3 h-3 rounded-full bg-secondary/60"></div>
                        <span class="ml-xs text-code-sm text-on-surface-variant">authenticator</span>
                    </div>
                    <div class="space-y-sm">
                        @php
                            $codes = [
                                ['icon' => 'shield', 'color' => 'primary', 'service' => 'GitHub', 'label' => 'personal', 'otp' => '482 901', 'progress' => 65],
                                ['icon' => 'mail', 'color' => 'secondary', 'service' => 'Google', 'label' => 'work', 'otp' => '739 254', 'progress' => 40],
                                ['icon' => 'chat', 'color' => 'tertiary', 'service' => 'Slack', 'label' => 'team', 'otp' => '516 083', 'progress' => 85],
                            ];
                        @endphp
                        @foreach ($codes as $code)
                            <div class="code-row flex items-center justify-between bg-surface-container-low/80 rounded-xl px-sm py-3.5 hover:bg-surface-container transition-colors duration-200 cursor-pointer">
                                <div class="flex items-center gap-sm">
                                    <span class="material-symbols-outlined text-{{ $code['color'] }} text-[20px]">{{ $code['icon'] }}</span>
                                    <div>
                                        <p class="text-label-sm font-semibold text-on-surface">{{ $code['service'] }}</p>
                                        <p class="text-code-sm text-on-surface-variant">{{ $code['label'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[24px] sm:text-otp-display font-mono text-primary tracking-widest">{{ $code['otp'] }}</p>
                                    <div class="w-24 h-1 bg-outline-variant/50 rounded-full mt-1.5 ml-auto overflow-hidden">
                                        <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ $code['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-md">
        <div class="max-w-container-max mx-auto text-center reveal">
            <div class="bg-primary-container rounded-3xl px-md py-xl sm:px-xl relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent pointer-events-none"></div>
                <span class="material-symbols-outlined text-on-primary-container text-[48px] mb-sm block relative z-10 animate-float-slow" style="font-variation-settings: 'FILL' 1;">shield</span>
                <h2 class="text-headline-md sm:text-headline-lg font-bold text-on-primary-container relative z-10" style="text-wrap: balance;">Secure your accounts today.</h2>
                <p class="mt-sm text-body-md text-on-primary-container/80 max-w-md mx-auto relative z-10" style="text-wrap: pretty;">Set up two-factor authentication in minutes. Free, self-hosted, and under your control.</p>
                <div class="mt-md flex flex-col sm:flex-row items-center justify-center gap-sm relative z-10">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" wire:navigate class="w-full sm:w-auto inline-flex items-center justify-center gap-xs px-lg py-sm bg-primary text-on-primary rounded-lg font-label-sm text-label-sm tracking-wide btn-press hover:shadow-xl hover:shadow-primary/25 transition-all duration-300">
                            Create your account
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-lg px-md border-t border-outline-variant/30">
        <div class="max-w-container-max mx-auto flex flex-col sm:flex-row items-center justify-between gap-sm text-label-sm text-on-surface-variant">
            <div class="flex items-center gap-xs">
                <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">shield</span>
                <span class="font-medium text-on-surface">SecureAuth</span>
                <span class="text-outline">·</span>
                <span>Vigilant &amp; Precise</span>
            </div>
            <p>Built with Laravel + Livewire</p>
        </div>
    </footer>

</body>
</html>
