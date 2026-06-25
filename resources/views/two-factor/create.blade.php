<x-layouts.app>
    <header class="mb-lg animate-fade-in-up">
        <h2 class="text-headline-lg text-on-surface mb-base">Add New Account</h2>
        <p class="text-on-surface-variant">Securely connect a new service to generate one-time passwords.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-lg stagger-in">
        {{-- Section 1: Scan QR Code --}}
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/50 p-md flex flex-col glow-hover transition-all duration-300 group">
            <div class="flex items-center gap-xs mb-sm text-primary">
                <span class="material-symbols-outlined transition-transform duration-300 group-hover:scale-110">qr_code_scanner</span>
                <h3 class="text-[16px] font-bold">Scan QR Code</h3>
            </div>
            <p class="text-label-sm text-on-surface-variant mb-md">Point your camera at the QR code provided by the service to automatically configure your account.</p>

            <div class="relative w-full aspect-square bg-surface-container/50 border-2 border-dashed border-outline-variant/50 rounded-xl flex items-center justify-center overflow-hidden group cursor-pointer mt-auto transition-all duration-300 hover:border-primary/50" id="scanner-container">
                <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <video id="scanner-video" class="w-full h-full object-cover hidden" autoplay playsinline></video>
                <div class="absolute left-0 right-0 h-[2px] bg-primary/50 top-1/4 animate-scan pointer-events-none"></div>
                <div class="text-center z-10 relative" id="scanner-placeholder">
                    <span class="material-symbols-outlined text-[48px] text-outline/50 mb-xs block group-hover:text-primary transition-all duration-300 group-hover:scale-110">photo_camera</span>
                    <span class="text-label-sm text-outline group-hover:text-primary transition-colors duration-300">Tap to activate camera</span>
                </div>
                {{-- Animated corners --}}
                <div class="absolute top-4 left-4 w-7 h-7 border-t-2 border-l-2 border-primary rounded-tl-md transition-all duration-300 group-hover:w-8 group-hover:h-8"></div>
                <div class="absolute top-4 right-4 w-7 h-7 border-t-2 border-r-2 border-primary rounded-tr-md transition-all duration-300 group-hover:w-8 group-hover:h-8"></div>
                <div class="absolute bottom-4 left-4 w-7 h-7 border-b-2 border-l-2 border-primary rounded-bl-md transition-all duration-300 group-hover:w-8 group-hover:h-8"></div>
                <div class="absolute bottom-4 right-4 w-7 h-7 border-b-2 border-r-2 border-primary rounded-br-md transition-all duration-300 group-hover:w-8 group-hover:h-8"></div>
                <div id="scanner-overlay" class="absolute inset-0 bg-on-surface/70 backdrop-blur-sm flex items-center justify-center hidden z-20">
                    <div class="text-center animate-scale-in">
                        <span class="material-symbols-outlined text-[48px] text-secondary-fixed mb-xs block">check_circle</span>
                        <p class="text-label-sm text-secondary-fixed font-medium">QR Code Detected!</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-sm mt-sm">
                <button onclick="startScanner()" id="start-scan-btn"
                        class="flex-1 bg-primary text-on-primary text-label-sm font-label-sm py-2.5 rounded-xl btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 shadow-sm flex items-center justify-center gap-xs">
                    <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                    Start Camera
                </button>
                <button onclick="stopScanner()" id="stop-scan-btn"
                        class="flex-1 py-2.5 text-label-sm font-label-sm text-on-surface-variant bg-surface-container-low border border-outline-variant/50 rounded-xl btn-press hover:bg-surface-container transition-all duration-200 hidden">
                    Stop Camera
                </button>
            </div>
        </div>

        {{-- Section 2: Manual Entry --}}
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/50 p-md flex flex-col glow-hover transition-all duration-300 group">
            <div class="flex items-center gap-xs mb-sm text-primary">
                <span class="material-symbols-outlined transition-transform duration-300 group-hover:scale-110">keyboard</span>
                <h3 class="text-[16px] font-bold">Manual Entry</h3>
            </div>
            <p class="text-label-sm text-on-surface-variant mb-md">Enter the details provided by the service manually if you cannot scan a QR code.</p>

            <form action="{{ route('two-factor.store') }}" method="POST" class="flex flex-col gap-md flex-1">
                @csrf
                <div>
                    <label class="block text-on-surface mb-xs text-label-sm font-label-sm" for="account_name">Account Name</label>
                    <input type="text" name="label" id="account_name" value="{{ old('label') }}" required
                           placeholder="e.g., My Bank, GitHub"
                           class="w-full bg-surface-container-lowest/80 border border-outline-variant/50 rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50"
                           autofocus>
                    @error('label') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-on-surface mb-xs text-label-sm font-label-sm" for="secret_key">Secret Key</label>
                    <div class="relative">
                        <input type="text" name="secret" id="secret_key" value="{{ old('secret') }}"
                               placeholder="Enter base32 key"
                               class="w-full bg-surface-container-lowest/80 border border-outline-variant/50 rounded-xl pl-sm pr-12 py-3 font-mono text-code-sm text-on-surface focus:outline-none input-glow transition-all duration-300 uppercase tracking-wider placeholder:text-on-surface-variant/50"
                               maxlength="64">
                        <button type="button" onclick="pasteFromClipboard()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-all duration-200 hover:scale-110">
                            <span class="material-symbols-outlined text-[20px]">content_paste</span>
                        </button>
                    </div>
                    <p class="text-[11px] text-on-surface-variant/70 mt-1">Spaces are ignored.</p>
                    @error('secret') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-on-surface mb-xs text-label-sm font-label-sm" for="issuer">Issuer</label>
                    <input type="text" name="issuer" id="issuer" value="{{ old('issuer') }}"
                           placeholder="e.g. Google, GitHub, Discord"
                           class="w-full bg-surface-container-lowest/80 border border-outline-variant/50 rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50">
                    @error('issuer') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div class="mt-auto pt-sm flex items-center justify-end gap-sm border-t border-outline-variant/30">
                    <a href="{{ route('two-factor.index') }}" wire:navigate
                       class="text-label-sm text-on-surface-variant hover:text-on-surface px-md py-2 rounded-xl transition-colors btn-press">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-primary text-on-primary text-label-sm font-label-sm px-md py-2 rounded-xl btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 shadow-sm flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Save Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-lg flex items-center justify-center gap-xs text-on-surface-variant/60 animate-fade-in-up">
        <span class="material-symbols-outlined text-[16px]">lock</span>
        <span class="text-[12px]">All keys are encrypted locally on this device.</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        let scannerStream = null;
        let scanInterval = null;

        function startScanner() {
            const video = document.getElementById('scanner-video');
            const placeholder = document.getElementById('scanner-placeholder');
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            }).then(stream => {
                scannerStream = stream;
                video.srcObject = stream;
                video.play();
                video.classList.remove('hidden');
                placeholder.classList.add('hidden');
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
            }).catch(() => alert('Camera not available. Use Manual Entry instead.'));
        }

        function stopScanner() {
            if (scanInterval) clearInterval(scanInterval);
            if (scannerStream) { scannerStream.getTracks().forEach(t => t.stop()); scannerStream = null; }
            const video = document.getElementById('scanner-video');
            const placeholder = document.getElementById('scanner-placeholder');
            video.classList.add('hidden');
            placeholder.classList.remove('hidden');
            document.getElementById('start-scan-btn').classList.remove('hidden');
            document.getElementById('stop-scan-btn').classList.add('hidden');
        }

        function handleQRCode(data) {
            stopScanner();
            document.getElementById('scanner-overlay').classList.remove('hidden');
            setTimeout(() => document.getElementById('scanner-overlay').classList.add('hidden'), 1500);
            if (data.startsWith('otpauth://totp/')) {
                const url = new URL(data);
                document.querySelector('input[name="label"]').value = decodeURIComponent(url.pathname.replace('/totp/', ''));
                document.querySelector('input[name="secret"]').value = url.searchParams.get('secret') || '';
                document.querySelector('input[name="issuer"]').value = url.searchParams.get('issuer') || '';
            } else {
                document.querySelector('input[name="secret"]').value = data;
            }
        }

        function pasteFromClipboard() {
            navigator.clipboard.readText().then(text => {
                document.getElementById('secret_key').value = text.toUpperCase().replace(/\s/g, '');
            }).catch(() => {});
        }
    </script>
</x-layouts.app>
