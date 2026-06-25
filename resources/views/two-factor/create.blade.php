<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('two-factor.index') }}" class="btn-ghost p-2 -ml-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-white tracking-tight">Add Account</h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Tabs --}}
            <div class="glass rounded-2xl p-1.5 flex gap-1 mb-6">
                <button onclick="switchTab('manual')" id="tab-manual"
                        class="tab-btn flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 text-white border border-indigo-500/20">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Manual Entry
                    </div>
                </button>
                <button onclick="switchTab('scan')" id="tab-scan"
                        class="tab-btn flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 text-gray-500 hover:text-gray-300">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        Scan QR Code
                    </div>
                </button>
            </div>

            {{-- Manual Entry Tab --}}
            <div id="panel-manual" class="animate-fade-in">
                <div class="glass rounded-3xl p-6">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-white mb-1">Manual Setup</h2>
                        <p class="text-sm text-gray-500">Enter your account details and secret key from your service</p>
                    </div>

                    <form action="{{ route('two-factor.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="space-y-1.5">
                            <label class="label-dark">Account Name <span class="text-rose-400">*</span></label>
                            <input type="text" name="label" value="{{ old('label') }}" required
                                   placeholder="e.g. john@gmail.com"
                                   class="input-dark" autofocus>
                            @error('label')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="label-dark">Issuer</label>
                            <input type="text" name="issuer" value="{{ old('issuer') }}"
                                   placeholder="e.g. Google, GitHub, Discord"
                                   class="input-dark">
                            @error('issuer')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="label-dark">Secret Key <span class="text-gray-600 font-normal normal-case">(optional)</span></label>
                            <input type="text" name="secret" value="{{ old('secret') }}"
                                   placeholder="Paste secret from your service"
                                   class="input-dark font-mono text-xs tracking-wider"
                                   maxlength="64">
                            <p class="text-[11px] text-gray-600">Paste the secret key from your service. Leave empty to generate a new one.</p>
                            @error('secret')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary w-full py-3 text-base flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Account
                        </button>
                    </form>
                </div>
            </div>

            {{-- QR Scan Tab --}}
            <div id="panel-scan" class="hidden animate-fade-in">
                <div class="glass rounded-3xl p-6">
                    <div class="mb-6 text-center">
                        <h2 class="text-lg font-bold text-white mb-1">Scan QR Code</h2>
                        <p class="text-sm text-gray-500">Point your camera at the QR code</p>
                    </div>

                    <div class="relative rounded-2xl overflow-hidden bg-black/50 aspect-square mb-5 border border-white/10" id="scanner-container">
                        <video id="scanner-video" class="w-full h-full object-cover" autoplay playsinline></video>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-52 h-52 border-2 border-white/30 rounded-3xl relative">
                                <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-indigo-400 rounded-tl-xl"></div>
                                <div class="absolute top-0 right-0 w-6 h-6 border-t-2 border-r-2 border-indigo-400 rounded-tr-xl"></div>
                                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-2 border-l-2 border-indigo-400 rounded-bl-xl"></div>
                                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-indigo-400 rounded-br-xl"></div>
                                <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-indigo-400 to-transparent scan-line"></div>
                            </div>
                        </div>
                        <div id="scanner-overlay" class="absolute inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center hidden">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-green-500/30">
                                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-green-400 font-medium">QR Code Detected!</p>
                            </div>
                        </div>
                    </div>

                    <button onclick="startScanner()" id="start-scan-btn"
                            class="btn-primary w-full py-3 flex items-center justify-center gap-2 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Start Camera
                    </button>
                    <button onclick="stopScanner()" id="stop-scan-btn"
                            class="w-full py-3 text-sm font-medium text-gray-400 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 transition hidden">
                        Stop Camera
                    </button>

                    <p class="text-xs text-gray-600 text-center mt-4">Camera not working? Use Manual Entry tab instead</p>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('two-factor.index') }}" class="text-sm text-gray-500 hover:text-indigo-400 transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to accounts
                </a>
            </div>
        </div>
    </div>

    <style>
        .scan-line {
            animation: scan 2s ease-in-out infinite;
        }
        @keyframes scan {
            0%, 100% { transform: translateY(-60px); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            50% { transform: translateY(60px); }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        let scannerStream = null;
        let scanInterval = null;

        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.className = 'tab-btn flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 text-gray-500 hover:text-gray-300';
            });
            document.getElementById('panel-manual').classList.add('hidden');
            document.getElementById('panel-scan').classList.add('hidden');

            const selected = document.getElementById('tab-' + tab);
            selected.className = 'tab-btn flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 text-white border border-indigo-500/20';
            document.getElementById('panel-' + tab).classList.remove('hidden');

            if (tab !== 'scan') stopScanner();
        }

        function startScanner() {
            const video = document.getElementById('scanner-video');
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            }).then(stream => {
                scannerStream = stream;
                video.srcObject = stream;
                video.play();
                document.getElementById('start-scan-btn').classList.add('hidden');
                document.getElementById('stop-scan-btn').classList.remove('hidden');

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                scanInterval = setInterval(() => {
                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const code = jsQR(ctx.getImageData(0, 0, canvas.width, canvas.height).data, canvas.width, canvas.height);
                        if (code && code.data) handleQRCode(code.data);
                    }
                }, 300);
            }).catch(() => alert('Camera not available. Use Manual Entry tab.'));
        }

        function stopScanner() {
            if (scanInterval) clearInterval(scanInterval);
            if (scannerStream) { scannerStream.getTracks().forEach(t => t.stop()); scannerStream = null; }
            document.getElementById('start-scan-btn').classList.remove('hidden');
            document.getElementById('stop-scan-btn').classList.add('hidden');
        }

        function handleQRCode(data) {
            stopScanner();
            document.getElementById('scanner-overlay').classList.remove('hidden');
            if (data.startsWith('otpauth://totp/')) {
                const url = new URL(data);
                document.querySelector('input[name="label"]').value = decodeURIComponent(url.pathname.replace('/totp/', ''));
                document.querySelector('input[name="secret"]').value = url.searchParams.get('secret') || '';
                document.querySelector('input[name="issuer"]').value = url.searchParams.get('issuer') || '';
            } else {
                document.querySelector('input[name="secret"]').value = data;
            }
            switchTab('manual');
        }
    </script>
</x-app-layout>
