<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.querySelector('[data-search]');
    const toggle = document.querySelector('.search-toggle');

    if (!searchForm || !toggle) return;

    toggle.addEventListener('click', () => {
        searchForm.classList.toggle('is-open');

        const input = searchForm.querySelector('input[name="q"]');
        if (input) input.focus();
    });
});
</script>
