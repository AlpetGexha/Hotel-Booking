<script>
    // Apply theme immediately before DOM content loads to prevent flash
    (function() {
        const savedAppearance = localStorage.getItem('appearance') || 'light';
        const root = document.documentElement;

        // Remove any existing theme classes
        root.classList.remove('dark', 'light');

        // Apply the appropriate theme
        if (savedAppearance === 'system') {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            root.classList.add(prefersDark ? 'dark' : 'light');
        } else {
            root.classList.add(savedAppearance);
        }
    })();
</script>
