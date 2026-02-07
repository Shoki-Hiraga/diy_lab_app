<script>
(() => {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.search-toggle');
        if (!btn) return;

        const searchContainer =
            document.getElementById('search-container');
        const searchInput = searchContainer?.querySelector(
            '.header-search-form input[name="q"]'
        );

        if (!searchContainer) return;

        e.preventDefault();
        searchContainer.classList.toggle('is-open');

        if (searchContainer.classList.contains('is-open')) {
            searchInput?.focus();
        }
    });
})();
</script>
