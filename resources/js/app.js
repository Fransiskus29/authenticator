function initTheme() {
    const stored = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = stored || (prefersDark ? 'dark' : 'light');
    document.documentElement.classList.toggle('dark', theme === 'dark');
    return theme;
}

function toggleTheme() {
    const html = document.documentElement;
    html.classList.add('theme-transition');
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateToggleIcon(isDark);
    setTimeout(() => html.classList.remove('theme-transition'), 300);
}

function updateToggleIcon(isDark) {
    document.querySelectorAll('.theme-toggle').forEach(btn => {
        const icon = btn.querySelector('.material-symbols-outlined');
        if (icon) icon.textContent = isDark ? 'light_mode' : 'dark_mode';
    });
}

// Scroll reveal observer
function initReveal() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
}

// Improved toast
function showToast(msg, duration = 2500) {
    let toast = document.getElementById('toast');
    if (!toast) return;
    const text = document.getElementById('toast-text');
    if (text) text.textContent = msg;
    toast.classList.remove('hidden', 'animate-toast-out');
    toast.classList.add('animate-toast-in');
    clearTimeout(toast._hideTimer);
    toast._hideTimer = setTimeout(() => {
        toast.classList.add('animate-toast-out');
        setTimeout(() => toast.classList.add('hidden'), 300);
    }, duration);
}

window.toggleTheme = window.toggleTheme || toggleTheme;
window.showToast = showToast;

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    updateToggleIcon(document.documentElement.classList.contains('dark'));
    initReveal();
});

document.addEventListener('livewire:navigated', () => {
    initTheme();
    updateToggleIcon(document.documentElement.classList.contains('dark'));
    initReveal();
});
