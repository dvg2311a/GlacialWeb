function setTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    try {
        localStorage.setItem('theme', theme);
    } catch (e) {
        // ignore
    }
}

function initTheme() {
    try {
        const stored = localStorage.getItem('theme');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const useDark = stored ? stored === 'dark' : prefersDark;
        setTheme(useDark ? 'dark' : 'light');
    } catch (e) {
        // fallback
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        setTheme(prefersDark ? 'dark' : 'light');
    }
}

export function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    setTheme(isDark ? 'light' : 'dark');
}

export function isDark() {
    return document.documentElement.classList.contains('dark');
}

// Initialize on import
if (typeof window !== 'undefined') {
    initTheme();
    window.toggleTheme = toggleTheme;
}

export default {
    initTheme,
    toggleTheme,
    isDark,
};
