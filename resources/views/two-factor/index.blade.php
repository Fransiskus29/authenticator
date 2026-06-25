<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Authenticator</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ count($accounts) }} {{ Str::plural('account', count($accounts)) }}</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="document.getElementById('import-modal').classList.remove('hidden')" class="btn-ghost text-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span class="hidden sm:inline">Import</span>
                </button>
                <button onclick="exportAccounts()" class="btn-ghost text-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span class="hidden sm:inline">Export</span>
                </button>
                <a href="{{ route('two-factor.create') }}" class="btn-primary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Add
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3" id="accounts-list">

            @if (session('success'))
                <div class="glass rounded-2xl p-4 border-green-500/30 bg-green-500/5 animate-slide-up">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-sm text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (count($accounts) > 2 || request('q'))
                <form method="GET" action="{{ route('two-factor.index') }}" class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search accounts..." class="input-dark pl-11 pr-10" autocomplete="off">
                    @if (request('q'))
                        <a href="{{ route('two-factor.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>
            @endif

            @forelse ($accounts as $index => $account)
                <div class="account-card glass glass-hover rounded-2xl overflow-hidden opacity-0 animate-slide-up stagger-{{ min($index + 1, 6) }}"
                     data-account-id="{{ $account->id }}"
                     data-url="{{ route('two-factor.code', $account) }}">
                    <div class="p-5">
                        <div class="flex items-center gap-4">
                            <div class="relative flex-shrink-0">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-xl
                                    {{ match(($account->id % 6)) {
                                        0 => 'bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-500/30',
                                        1 => 'bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-500/30',
                                        2 => 'bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg shadow-violet-500/30',
                                        3 => 'bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-amber-500/30',
                                        4 => 'bg-gradient-to-br from-rose-500 to-pink-600 shadow-lg shadow-rose-500/30',
                                        5 => 'bg-gradient-to-br from-cyan-500 to-sky-600 shadow-lg shadow-cyan-500/30',
                                        default => 'bg-gradient-to-br from-gray-500 to-gray-600',
                                    } }}">
                                    {{ strtoupper(substr($account->label, 0, 1)) }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-gray-900 flex items-center justify-center pulse-dot">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-sm font-semibold text-white truncate">{{ $account->label }}</h3>
                                    @if ($account->issuer)
                                        <span class="text-[11px] text-gray-500 bg-white/5 px-2 py-0.5 rounded-full">{{ $account->issuer }}</span>
                                    @endif
                                </div>
                                <div class="font-mono text-[28px] font-bold tracking-[0.35em] text-white code-display leading-none"
                                     id="code-{{ $account->id }}">{{ $codes[$account->id] ?? '------' }}</div>
                            </div>

                            <div class="flex flex-col items-center gap-2 flex-shrink-0">
                                <div class="relative w-12 h-12">
                                    <svg class="w-12 h-12 -rotate-90" viewBox="0 0 40 40">
                                        <circle cx="20" cy="20" r="17" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="3" />
                                        <circle cx="20" cy="20" r="17" fill="none" stroke-width="3" stroke-linecap="round"
                                                stroke-dasharray="106.81" stroke-dashoffset="0"
                                                class="countdown-ring transition-all duration-1000 ease-linear"
                                                id="ring-{{ $account->id }}"
                                                style="stroke: url(#ring-gradient-{{ $account->id }}); filter: drop-shadow(0 0 6px rgba(99,102,241,0.5));" />
                                        <defs>
                                            <linearGradient id="ring-gradient-{{ $account->id }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                                <stop offset="0%" stop-color="#818cf8" />
                                                <stop offset="100%" stop-color="#c084fc" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                    <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-300 countdown-text font-mono"
                                          id="timer-{{ $account->id }}">30</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-5 py-3 border-t border-white/5 bg-white/[0.02]">
                        <div class="h-1.5 flex-1 bg-white/5 rounded-full overflow-hidden mr-4">
                            <div class="h-full rounded-full transition-all duration-1000 ease-linear progress-bar"
                                 id="progress-{{ $account->id }}" style="width: 100%; background: linear-gradient(90deg, #818cf8, #c084fc);"></div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button onclick="copyCode({{ $account->id }})" class="btn-ghost p-2 rounded-xl" title="Copy code">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                            <button onclick="confirmDelete({{ $account->id }}, '{{ addslashes($account->label) }}')"
                                    class="btn-ghost p-2 rounded-xl text-gray-500 hover:!text-red-400 hover:!bg-red-500/10" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass rounded-3xl p-12 text-center opacity-0 animate-slide-up">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-indigo-500/20">
                        <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">{{ request('q') ? 'No results found' : 'No accounts yet' }}</h3>
                    <p class="text-sm text-gray-500 mb-8 max-w-xs mx-auto">
                        {{ request('q') ? 'Try a different search term' : 'Add your first 2FA account to start generating authentication codes' }}
                    </p>
                    @if (!request('q'))
                        <a href="{{ route('two-factor.create') }}" class="btn-primary inline-flex items-center gap-2 text-base px-8 py-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            Add Your First Account
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="glass rounded-3xl max-w-sm w-full p-6 animate-slide-up border-red-500/20">
                <div class="w-14 h-14 bg-red-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-500/20">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-white text-center mb-2">Delete Account?</h3>
                <p class="text-sm text-gray-400 text-center mb-6">Are you sure you want to delete <span id="delete-name" class="font-semibold text-white"></span>?</p>
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="flex-1 py-2.5 text-sm font-medium text-gray-400 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 transition">Cancel</button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 text-sm font-medium text-white bg-red-500/80 border border-red-500/30 rounded-xl hover:bg-red-500 transition">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div id="import-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="document.getElementById('import-modal').classList.add('hidden')"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="glass rounded-3xl max-w-md w-full p-6 animate-slide-up">
                <div class="w-14 h-14 bg-indigo-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-indigo-500/20">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-white text-center mb-2">Import Backup</h3>
                <p class="text-sm text-gray-400 text-center mb-6">Paste your encrypted backup data below</p>
                <form action="{{ route('two-factor.import') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="backup_data" rows="4" required class="input-dark font-mono text-xs" placeholder="Paste backup data here..."></textarea>
                        @error('backup_data') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="flex-1 py-2.5 text-sm font-medium text-gray-400 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 transition">Cancel</button>
                        <button type="submit" class="flex-1 btn-primary py-2.5">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 hidden">
        <div class="glass rounded-2xl px-5 py-3 flex items-center gap-2.5 glow-indigo border-indigo-500/30">
            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span id="toast-text" class="text-sm font-medium text-white">Done!</span>
        </div>
    </div>

    <style>
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }
        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            50% { box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
        }
        .code-display { transition: all 0.3s ease; }
    </style>

    <script>
        const accounts = @json($accounts->map(fn($a) => ['id' => $a->id, 'url' => route('two-factor.code', $a)]));

        function refreshCode(account) {
            fetch(account.url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                const codeEl = document.getElementById('code-' + account.id);
                const timerEl = document.getElementById('timer-' + account.id);
                const ringEl = document.getElementById('ring-' + account.id);
                const progressEl = document.getElementById('progress-' + account.id);

                if (codeEl) {
                    const oldCode = codeEl.textContent.trim().replace(/\s/g, '');
                    if (oldCode !== data.code) {
                        codeEl.classList.add('animate-code-flash');
                        codeEl.textContent = data.formatted;
                        setTimeout(() => codeEl.classList.remove('animate-code-flash'), 600);
                    }
                }
                if (timerEl) timerEl.textContent = data.remaining;
                if (ringEl) {
                    const circumference = 2 * Math.PI * 17;
                    ringEl.style.strokeDashoffset = circumference * (1 - data.remaining / 30);
                    if (data.remaining <= 5) {
                        ringEl.style.filter = 'drop-shadow(0 0 8px rgba(239,68,68,0.6))';
                        ringEl.style.stroke = '#ef4444';
                        if (timerEl) { timerEl.style.color = '#ef4444'; timerEl.classList.add('animate-pulse'); }
                    } else {
                        ringEl.style.filter = 'drop-shadow(0 0 6px rgba(99,102,241,0.5))';
                        ringEl.style.stroke = '';
                        if (timerEl) { timerEl.style.color = ''; timerEl.classList.remove('animate-pulse'); }
                    }
                }
                if (progressEl) {
                    progressEl.style.width = (data.remaining / 30 * 100) + '%';
                    progressEl.style.background = data.remaining <= 5
                        ? 'linear-gradient(90deg, #ef4444, #f87171)'
                        : 'linear-gradient(90deg, #818cf8, #c084fc)';
                }
            })
            .catch(() => {});
        }

        function refreshAll() { accounts.forEach(a => refreshCode(a)); }

        function copyCode(accountId) {
            const codeEl = document.getElementById('code-' + accountId);
            if (codeEl) {
                const code = codeEl.textContent.trim().replace(/\s/g, '');
                navigator.clipboard.writeText(code).then(() => showToast('Code copied to clipboard!'))
                .catch(() => {
                    const ta = document.createElement('textarea');
                    ta.value = code;
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                    showToast('Code copied to clipboard!');
                });
            }
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-text').textContent = msg;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2000);
        }

        function confirmDelete(id, name) {
            document.getElementById('delete-name').textContent = name;
            document.getElementById('delete-form').action = '{{ url("authenticator") }}/' + id;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function exportAccounts() {
            fetch('{{ route("two-factor.export") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                navigator.clipboard.writeText(data.data).then(() => {
                    showToast('Backup copied to clipboard! (' + data.count + ' accounts)');
                }).catch(() => {
                    const ta = document.createElement('textarea');
                    ta.value = data.data;
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                    showToast('Backup copied to clipboard! (' + data.count + ' accounts)');
                });
            })
            .catch(() => showToast('Export failed'));
        }

        refreshAll();
        setInterval(refreshAll, 1000);
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeDeleteModal();
                document.getElementById('import-modal').classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
