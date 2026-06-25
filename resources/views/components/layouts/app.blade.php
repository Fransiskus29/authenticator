<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f8f9ff" id="theme-color-meta">

    <title>{{ config('app.name', 'SecureAuth') }}</title>

    <script>
        (function() {
            var t = localStorage.getItem('theme');
            var d = (!t && window.matchMedia('(prefers-color-scheme: dark)').matches) || t === 'dark';
            if (d) {
                document.documentElement.classList.add('dark');
                document.getElementById('theme-color-meta')?.setAttribute('content', '#161820');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-sans text-body-md min-h-screen antialiased flex">
    @php
        $user = auth()->user();
        $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
    @endphp

    {{-- Desktop sidebar --}}
    <aside class="w-[280px] h-screen fixed left-0 top-0 bg-surface border-r border-outline-variant flex flex-col py-md px-sm z-20 hidden md:flex">
        <div class="mb-xl flex items-center gap-xs px-sm">
            <span class="material-symbols-outlined text-primary text-[32px] font-bold" style="font-variation-settings: 'FILL' 1;">shield</span>
            <div>
                <h1 class="font-sans text-headline-md font-bold text-primary">SecureAuth</h1>
                <p class="text-label-sm text-on-surface-variant">Vigilant &amp; Precise</p>
            </div>
        </div>

        <nav class="flex-1 space-y-xs">
            <a href="{{ route('two-factor.index') }}" wire:navigate
               class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                      {{ request()->routeIs('two-factor.index') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('two-factor.index') ? 'filled' : '' }}">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('two-factor.create') }}" wire:navigate
               class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                      {{ request()->routeIs('two-factor.create') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('two-factor.create') ? 'filled' : '' }}">add_circle</span>
                <span>Add Account</span>
            </a>
            <a href="{{ route('profile') }}" wire:navigate
               class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                      {{ request()->routeIs('profile') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('profile') ? 'filled' : '' }}">security</span>
                <span>Security Settings</span>
            </a>
        </nav>

        <div class="mt-auto pt-lg border-t border-outline-variant space-y-sm">
            <div class="flex items-center justify-between px-sm">
                <div class="flex items-center gap-sm">
                    <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container text-xs font-bold">
                        {{ $initial }}
                    </div>
                    <span class="text-label-sm text-on-surface truncate max-w-[140px]">{{ $user->name ?? 'User' }}</span>
                </div>
                <button onclick="toggleTheme()" class="theme-toggle text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors" title="Toggle dark mode">
                    <span class="material-symbols-outlined">dark_mode</span>
                </button>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm text-on-surface-variant hover:bg-surface-container">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col md:ml-[280px] w-full min-h-screen">
        <header class="h-16 fixed top-0 right-0 w-full md:w-[calc(100%-280px)] bg-surface/80 backdrop-blur-lg border-b border-outline-variant flex justify-between items-center px-sm sm:px-md z-10">
            <div class="flex items-center gap-xs">
                <button onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')" class="md:hidden text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h2 class="text-headline-md text-primary font-bold hidden sm:block">Authenticator</h2>
                <h2 class="text-headline-md text-primary font-bold sm:hidden">SecureAuth</h2>
            </div>
            <div class="flex items-center gap-xs">
                <button onclick="toggleTheme()" class="theme-toggle text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors" title="Toggle dark mode">
                    <span class="material-symbols-outlined">dark_mode</span>
                </button>
                <a href="{{ route('profile') }}" wire:navigate class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors">
                    <span class="material-symbols-outlined">settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors" title="Log out">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </form>
                <div class="sm:ml-xs sm:pl-xs sm:border-l border-outline-variant">
                    <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container text-xs font-bold">
                        {{ $initial }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Mobile sidebar --}}
        <div id="mobile-sidebar" class="hidden fixed inset-0 z-50 md:hidden">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"></div>
            <aside class="w-[280px] h-full bg-surface border-r border-outline-variant flex flex-col py-md px-sm relative z-10 animate-slide-up">
                <div class="mb-xl flex items-center gap-xs px-sm">
                    <span class="material-symbols-outlined text-primary text-[32px] font-bold" style="font-variation-settings: 'FILL' 1;">shield</span>
                    <div>
                        <h1 class="font-sans text-headline-md font-bold text-primary">SecureAuth</h1>
                        <p class="text-label-sm text-on-surface-variant">Vigilant &amp; Precise</p>
                    </div>
                </div>
                <nav class="flex-1 space-y-xs">
                    <a href="{{ route('two-factor.index') }}" wire:navigate onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"
                       class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                              {{ request()->routeIs('two-factor.index') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('two-factor.create') }}" wire:navigate onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"
                       class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                              {{ request()->routeIs('two-factor.create') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">add_circle</span>
                        <span>Add Account</span>
                    </a>
                    <a href="{{ route('profile') }}" wire:navigate onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"
                       class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                              {{ request()->routeIs('profile') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">security</span>
                        <span>Security Settings</span>
                    </a>
                </nav>
                <div class="mt-auto pt-lg border-t border-outline-variant space-y-sm">
                    <div class="flex items-center gap-sm px-sm mb-sm">
                        <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container text-xs font-bold">
                            {{ $initial }}
                        </div>
                        <span class="text-label-sm text-on-surface">{{ $user->name ?? 'User' }}</span>
                        <button onclick="toggleTheme()" class="theme-toggle ml-auto text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors" title="Toggle dark mode">
                            <span class="material-symbols-outlined">dark_mode</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm text-on-surface-variant hover:bg-surface-container">
                            <span class="material-symbols-outlined">logout</span>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            </aside>
        </div>

        <main class="flex-1 mt-16 p-sm sm:p-md md:p-lg overflow-y-auto bg-surface-bright">
            <div class="max-w-container-max mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
