/**
 * Alpine.js component for handling theme/appearance settings
 */
export default function themeHandler() {
    return {
        appearance: localStorage.getItem('appearance') || 'light', // Default to light mode
        
        init() {
            // Listen for Livewire events
            window.Livewire.on('appearance-changed', ({ mode }) => {
                this.updateAppearance(mode);
            });
            
            // Initialize theme based on saved preference or default to light mode
            document.documentElement.classList.add('light'); // Set light as base class
            this.updateAppearance(this.appearance);
            
            // Listen for system preference changes, but only apply if system mode is chosen
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (this.appearance === 'system') {
                    this.updateThemeClasses(e.matches ? 'dark' : 'light');
                }
            });
        },
        
        /**
         * Set appearance value and sync with Livewire
         */
        setAppearance(value) {
            // Update local value first for immediate feedback
            this.appearance = value;
            this.updateAppearance(value);
            
            // Sync with Livewire component
            this.$wire.appearance = value;
        },
        
        /**
         * Update theme appearance based on selected value
         */
        updateAppearance(value) {
            if (value === 'system') {
                const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                this.updateThemeClasses(isDarkMode ? 'dark' : 'light');
            } else {
                this.updateThemeClasses(value);
            }
            
            localStorage.setItem('appearance', value);
        },
        
        /**
         * Update theme classes on the document
         */
        updateThemeClasses(theme) {
            document.documentElement.classList.remove('dark', 'light');
            document.documentElement.classList.add(theme);
        }
    };
}
