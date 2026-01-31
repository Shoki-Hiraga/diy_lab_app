<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.reaction-form').forEach(form => {
        form.addEventListener('submit', async e => {
            e.preventDefault();

            const button = form.querySelector('.reaction-btn');
            const countEl = button.querySelector('.reaction-count');

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) return;

            const data = await response.json();

            // active 切り替え
            button.classList.toggle('active', data.activated);

            // 数更新
            countEl.textContent = data.count;
        });
    });
});
</script>