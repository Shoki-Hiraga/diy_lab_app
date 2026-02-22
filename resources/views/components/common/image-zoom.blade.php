<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    // 画像クリックで開く
    document.querySelectorAll('.zoomable').forEach(img => {
        img.addEventListener('click', function () {
            modalImage.src = this.dataset.full;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // 背景スクロール防止
        });
    });

    // モーダルクリックで閉じる
    modal.addEventListener('click', function () {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    });

});
</script>