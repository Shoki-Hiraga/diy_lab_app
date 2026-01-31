<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.querySelector('.search-toggle');
    const searchContainer = document.getElementById('search-container');
    const searchInput = searchContainer?.querySelector('input[name="q"]');

    if (!toggleBtn || !searchContainer) return;

    toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();
        searchContainer.classList.toggle('is-open');

        // 表示されたら自動フォーカス
        if (searchContainer.classList.contains('is-open')) {
            searchInput.focus();
        }
    });
});
</script>