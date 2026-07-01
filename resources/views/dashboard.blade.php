<x-layouts.app>
    <div class="flex justify-between items-end mb-lg animate-fade-in-up">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-base">Dashboard</h2>
            <p class="text-body-md text-on-surface-variant">Welcome back, <span class="font-medium text-on-surface">{{ auth()->user()->name }}</span>.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-sm mb-lg stagger-in">
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-md card-hover glow-hover group">
            <div class="flex items-center gap-sm mb-sm">
                <div class="w-11 h-11 rounded-xl bg-primary-container flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <span class="material-symbols-outlined text-on-primary-container text-[22px]">shield</span>
                </div>
                <h3 class="text-headline-md text-on-surface">Your 2FA Hub</h3>
            </div>
            <p class="text-body-md text-on-surface-variant mb-md leading-relaxed">View and manage all your authentication codes from one place.</p>
            <a href="{{ route('two-factor.index') }}" wire:navigate
               class="bg-primary text-on-primary text-label-sm font-label-sm px-md py-sm rounded-xl btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 shadow-sm inline-flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                Go to Authenticator
            </a>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-md card-hover glow-hover group">
            <div class="flex items-center gap-sm mb-sm">
                <div class="w-11 h-11 rounded-xl bg-secondary-container flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <span class="material-symbols-outlined text-on-secondary-container text-[22px]">add_circle</span>
                </div>
                <h3 class="text-headline-md text-on-surface">Add Account</h3>
            </div>
            <p class="text-body-md text-on-surface-variant mb-md leading-relaxed">Connect a new service to start generating codes.</p>
            <a href="{{ route('two-factor.create') }}" wire:navigate
               class="bg-primary-container text-on-primary-container text-label-sm font-label-sm px-md py-sm rounded-xl btn-press hover:shadow-lg hover:shadow-primary/15 transition-all duration-300 shadow-sm inline-flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Add New Account
            </a>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-md card-hover glow-hover group">
            <div class="flex items-center gap-sm mb-sm">
                <div class="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <span class="material-symbols-outlined text-on-surface-variant text-[22px]">security</span>
                </div>
                <h3 class="text-headline-md text-on-surface">Security</h3>
            </div>
            <p class="text-body-md text-on-surface-variant mb-md leading-relaxed">Manage your security settings and trusted devices.</p>
            <a href="{{ route('profile') }}" wire:navigate
               class="bg-surface-container border border-outline-variant/50 text-on-surface text-label-sm font-label-sm px-md py-sm rounded-xl btn-press hover:bg-surface-container-high transition-all duration-300 inline-flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">settings</span>
                Settings
            </a>
        </div>
    </div>
</x-layouts.app>
