<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('two-factor.index') }}" class="btn-ghost p-2 -ml-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-white tracking-tight">Setup Complete</h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass rounded-3xl p-8 text-center animate-slide-up">

                {{-- Success Badge --}}
                <div class="w-20 h-20 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-green-500/20 glow-green">
                    <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-white mb-1">{{ $account->label }}</h2>
                @if ($account->issuer)
                    <p class="text-sm text-gray-500 mb-6">{{ $account->issuer }}</p>
                @else
                    <div class="mb-6"></div>
                @endif

                <p class="text-sm text-gray-400 mb-8">Scan this QR code with your authenticator app or enter the secret key manually</p>

                {{-- QR Code --}}
                <div class="inline-block p-5 glass rounded-3xl mb-8 animate-pulse-glow">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&bgcolor=0f0f1a&color=ffffff&data={{ urlencode($qrCodeUrl) }}"
                        alt="QR Code for {{ $account->label }}"
                        class="block rounded-xl"
                    >
                </div>

                {{-- Secret Key --}}
                <div class="glass rounded-2xl p-5 mb-8 text-left">
                    <p class="label-dark mb-3">Secret Key (Manual Entry)</p>
                    <div class="flex items-center gap-2">
                        <code class="flex-1 text-sm font-mono tracking-widest text-indigo-300 select-all bg-black/30 px-4 py-3 rounded-xl border border-white/5 break-all">
                            {{ $account->secret }}
                        </code>
                        <button onclick="copySecret()" class="btn-ghost p-3 flex-shrink-0" title="Copy secret">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <a href="{{ route('two-factor.index') }}" class="btn-primary w-full py-3 text-base inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Done
                </a>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 hidden">
        <div class="glass rounded-2xl px-5 py-3 flex items-center gap-2.5 glow-indigo border-indigo-500/30">
            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span id="toast-text" class="text-sm font-medium text-white">Secret copied!</span>
        </div>
    </div>

    <script>
        function copySecret() {
            const secret = '{{ $account->secret }}';
            navigator.clipboard.writeText(secret).then(() => {
                const toast = document.getElementById('toast');
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 2000);
            }).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = secret;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                const toast = document.getElementById('toast');
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 2000);
            });
        }
    </script>
</x-app-layout>
