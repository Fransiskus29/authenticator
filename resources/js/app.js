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

window.toggleTheme = toggleTheme;

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    updateToggleIcon(document.documentElement.classList.contains('dark'));
});

document.addEventListener('livewire:navigated', () => {
    updateToggleIcon(document.documentElement.classList.contains('dark'));
});
