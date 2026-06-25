<x-layouts.app>
    <div class="max-w-[800px] mx-auto">
        <header class="mb-lg animate-fade-in-up">
            <h2 class="text-headline-lg text-on-surface mb-base">Setup Complete</h2>
            <p class="text-on-surface-variant">Your account has been added successfully.</p>
        </header>

        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/50 p-lg animate-scale-in shadow-xl shadow-black/5 dark:shadow-black/20">
            <div class="flex items-center gap-sm mb-md">
                <div class="w-11 h-11 rounded-xl bg-secondary-container flex items-center justify-center animate-pulse-glow">
                    <span class="material-symbols-outlined text-on-secondary-container text-[22px]">check_circle</span>
                </div>
                <h3 class="text-headline-md text-on-surface">{{ $account->label }}</h3>
                @if ($account->issuer)
                    <span class="text-label-sm text-on-surface-variant bg-surface-container px-2.5 py-0.5 rounded-full">{{ $account->issuer }}</span>
                @endif
            </div>

            <p class="text-body-md text-on-surface-variant mb-md">Scan this QR code with your authenticator app or enter the secret key manually.</p>

            <div class="flex flex-col md:flex-row gap-lg items-center md:items-start">
                {{-- QR Code --}}
                <div class="flex-shrink-0 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="inline-block p-4 sm:p-5 bg-surface-container-low/50 border border-outline-variant/30 rounded-2xl shadow-sm">
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&bgcolor=f8f9ff&color=0b1c30&data={{ urlencode($qrCodeUrl) }}"
                            alt="QR Code for {{ $account->label }}"
                            class="block rounded-xl w-[180px] h-[180px] sm:w-[200px] sm:h-[200px]"
                        >
                    </div>
                </div>

                {{-- Secret Key --}}
                <div class="flex-1 animate-fade-in-up" style="animation-delay: 0.3s;">
                    <p class="text-on-surface mb-xs text-label-sm font-label-sm">Secret Key (Manual Entry)</p>
                    <div class="bg-surface-container-low/50 border border-outline-variant/30 rounded-xl p-sm mb-sm">
                        <code class="text-code-sm font-mono tracking-widest text-primary select-all break-all block">
                            {{ $account->secret }}
                        </code>
                    </div>
                    <button onclick="copySecret()" class="text-primary hover:text-primary/80 text-label-sm font-label-sm flex items-center gap-xs transition-all duration-200 hover:gap-2">
                        <span class="material-symbols-outlined text-[16px]">content_copy</span>
                        Copy secret key
                    </button>
                </div>
            </div>

            <div class="mt-md pt-md border-t border-outline-variant/30">
                <a href="{{ route('two-factor.index') }}" wire:navigate
                   class="bg-primary text-on-primary text-label-sm font-label-sm px-md py-sm rounded-xl btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 shadow-sm inline-flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                    Done
                </a>
            </div>
        </div>

        <div class="mt-sm flex items-center justify-center gap-xs text-on-surface-variant/60 animate-fade-in-up" style="animation-delay: 0.4s;">
            <span class="material-symbols-outlined text-[16px]">lock</span>
            <span class="text-[12px]">All keys are encrypted locally on this device.</span>
        </div>
    </div>

    <div id="toast" class="fixed bottom-lg right-lg bg-inverse-surface text-inverse-on-surface px-md py-xs rounded-xl shadow-xl shadow-black/15 flex items-center gap-xs text-label-sm font-label-sm z-50 hidden">
        <span class="material-symbols-outlined text-secondary-fixed text-[20px]">check_circle</span>
        <span id="toast-text">Secret copied!</span>
    </div>

    <script>
        function copySecret() {
            const secret = '{{ $account->secret }}';
            navigator.clipboard.writeText(secret).then(() => {
                showToast('Secret copied!');
            }).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = secret;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showToast('Secret copied!');
            });
        }
    </script>
</x-layouts.app>
