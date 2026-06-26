<x-layouts.app>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-sm mb-lg animate-fade-in-up">
        <div>
            <div class="flex items-center gap-sm mb-base">
                <a href="{{ route('two-factor.index') }}" wire:navigate class="text-on-surface-variant hover:text-on-surface transition-colors duration-200 p-1 hover:scale-110">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <h2 class="text-headline-lg text-on-surface">Archived Accounts</h2>
            </div>
            <p class="text-body-md text-on-surface-variant">These accounts will be permanently deleted after 7 days.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-secondary-container/20 border border-secondary-container/50 rounded-xl p-4 mb-sm flex items-center gap-sm animate-slide-up">
            <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
            <p class="text-label-sm text-secondary">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-sm stagger-in">
        @forelse ($accounts as $account)
            <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-md relative group transition-all duration-300 opacity-75 hover:opacity-100">
                <div class="flex justify-between items-start mb-md">
                    <div class="flex items-center gap-sm">
                        <div class="w-11 h-11 rounded-xl bg-error-container/20 border border-outline-variant/50 flex items-center justify-center p-2">
                            <span class="material-symbols-outlined text-error text-[20px]">archive</span>
                        </div>
                        <div>
                            <h3 class="text-title-md text-on-surface">{{ $account->label }}</h3>
                            @if ($account->issuer)
                                <span class="text-label-xs text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">{{ $account->issuer }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-xs text-on-surface-variant/60 mb-md">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    <span class="text-[12px]">
                        Deleted {{ $account->deleted_at->diffForHumans() }}
                        · expires {{ $account->deleted_at->addDays(7)->format('M j') }}
                    </span>
                </div>

                <div class="flex gap-sm pt-sm border-t border-outline-variant/30">
                    <form action="/authenticator/{{ $account->id }}/restore" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full bg-secondary/10 text-secondary text-label-sm font-label-sm px-sm py-2 rounded-xl btn-press hover:bg-secondary/20 transition-all duration-200 flex items-center justify-center gap-xs">
                            <span class="material-symbols-outlined text-[16px]">restore</span>
                            Restore
                        </button>
                    </form>
                    <form action="/authenticator/{{ $account->id }}/force-delete" method="POST" class="flex-1" onsubmit="return confirm('Permanently delete {{ $account->label }}? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-error/10 text-error text-label-sm font-label-sm px-sm py-2 rounded-xl btn-press hover:bg-error/20 transition-all duration-200 flex items-center justify-center gap-xs">
                            <span class="material-symbols-outlined text-[16px]">delete_forever</span>
                            Delete Now
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-xl text-center animate-fade-in-up">
                <span class="material-symbols-outlined text-[64px] text-outline-variant/50 mb-md block">inbox</span>
                <h3 class="text-headline-md text-on-surface mb-xs">No archived accounts</h3>
                <p class="text-body-md text-on-surface-variant">Deleted accounts will appear here for 7 days before permanent removal.</p>
                <a href="{{ route('two-factor.index') }}" wire:navigate
                   class="mt-md inline-flex items-center gap-xs text-primary hover:text-primary/80 text-label-sm font-label-sm transition-colors duration-200">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Back to Authenticator
                </a>
            </div>
        @endforelse
    </div>
</x-layouts.app>
