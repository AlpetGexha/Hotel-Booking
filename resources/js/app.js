import Alpine from 'alpinejs'
import themeHandler from './components/theme'

// Register Alpine.js components
Alpine.data('theme', themeHandler);

// Apply theme immediately on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedAppearance = localStorage.getItem('appearance') || 'light';
    document.documentElement.classList.remove('dark', 'light');

    if (savedAppearance === 'system') {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.classList.add(prefersDark ? 'dark' : 'light');
    } else {
        document.documentElement.classList.add(savedAppearance);
    }
});

// Initialize Alpine.js
window.Alpine = Alpine
Alpine.start()
