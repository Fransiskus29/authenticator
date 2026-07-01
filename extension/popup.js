const API_URL = 'https://authenticator-2fa-2d3f24094b53.herokuapp.com/api/codes';
let refreshInterval = null;

// Init
document.addEventListener('DOMContentLoaded', async () => {
    const { token } = await chrome.storage.local.get('token');
    if (token) {
        showView('codes-view');
        loadCodes(token);
        startAutoRefresh(token);
    } else {
        showView('setup');
    }

    // Connect button
    document.getElementById('connect-btn').addEventListener('click', async () => {
        const token = document.getElementById('token-input').value.trim();
        if (!token) return;

        try {
            const res = await fetch(API_URL, {
                headers: { 'Authorization': `Bearer ${token}` },
            });

            if (res.ok) {
                await chrome.storage.local.set({ token });
                showView('codes-view');
                loadCodes(token);
                startAutoRefresh(token);
            } else {
                showError('Invalid token. Check and try again.');
            }
        } catch (e) {
            showError('Cannot reach SecureAuth. Check your connection.');
        }
    });

    // Disconnect
    document.getElementById('disconnect-btn').addEventListener('click', async () => {
        if (confirm('Disconnect from SecureAuth?')) {
            await chrome.storage.local.remove('token');
            stopAutoRefresh();
            showView('setup');
        }
    });

    // Refresh
    document.getElementById('refresh-btn').addEventListener('click', async () => {
        const { token } = await chrome.storage.local.get('token');
        if (token) loadCodes(token);
    });

    // Search
    document.getElementById('search').addEventListener('input', e => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('.account-item').forEach(item => {
            const label = item.dataset.label || '';
            const issuer = item.dataset.issuer || '';
            item.style.display = (label.includes(q) || issuer.includes(q)) ? '' : 'none';
        });
    });

    // Add accounts link
    document.getElementById('add-link')?.addEventListener('click', () => {
        chrome.tabs.create({ url: 'https://authenticator-2fa-2d3f24094b53.herokuapp.com/authenticator/create' });
    });
});

function showView(id) {
    ['setup', 'codes-view', 'loading'].forEach(v => {
        document.getElementById(v).classList.toggle('hidden', v !== id);
    });
}

function showError(msg) {
    const el = document.getElementById('setup-error');
    el.textContent = msg;
    el.classList.remove('hidden');
}

function startAutoRefresh(token) {
    stopAutoRefresh();
    refreshInterval = setInterval(() => loadCodes(token), 15000);
}

function stopAutoRefresh() {
    if (refreshInterval) clearInterval(refreshInterval);
}

async function loadCodes(token) {
    try {
        const res = await fetch(API_URL, {
            headers: { 'Authorization': `Bearer ${token}` },
        });

        if (!res.ok) {
            if (res.status === 401) {
                await chrome.storage.local.remove('token');
                stopAutoRefresh();
                showView('setup');
            }
            return;
        }

        const data = await res.json();
        renderAccounts(data.accounts);
    } catch (e) {
        // Silently fail on network errors
    }
}

function renderAccounts(accounts) {
    const list = document.getElementById('account-list');
    const empty = document.getElementById('empty-state');

    if (!accounts || accounts.length === 0) {
        list.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');
    list.innerHTML = accounts.map(a => {
        const formatted = `${a.code.slice(0, 3)} ${a.code.slice(3)}`;
        const timerClass = a.remaining <= 7 ? 'warn' : '';
        const icon = issuerIcon(a.issuer);

        return `
            <div class="account-item" data-label="${(a.label || '').toLowerCase()}" data-issuer="${(a.issuer || '').toLowerCase()}"
                 onclick="copyCode('${a.code}')">
                <div class="account-icon">${icon}</div>
                <div class="account-info">
                    <div class="account-label">${escapeHtml(a.label)}</div>
                    ${a.issuer ? `<div class="account-issuer">${escapeHtml(a.issuer)}</div>` : ''}
                </div>
                <div class="account-code">${formatted}</div>
                <div class="account-timer ${timerClass}">${a.remaining}s</div>
            </div>
        `;
    }).join('');
}

function issuerIcon(issuer) {
    const map = {
        google: '✉', github: '⌨', discord: '💬', microsoft: '💻',
        amazon: '🛒', facebook: '👥', twitter: '🐦', apple: '🍎',
    };
    return map[(issuer || '').toLowerCase()] || '🔑';
}

function escapeHtml(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
}

function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        showToast('Copied!');
    }).catch(() => {
        // Fallback
        const ta = document.createElement('textarea');
        ta.value = code;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        showToast('Copied!');
    });
}

function showToast(msg) {
    const existing = document.querySelector('.copied-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'copied-toast';
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 1200);
}
