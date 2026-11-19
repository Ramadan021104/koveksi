// Konfirmasi hapus
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function (e) {
        if (!confirm(this.getAttribute('data-confirm') || 'Yakin ingin menghapus?')) {
            e.preventDefault();
        }
    });
});

// Preview gambar upload
document.querySelectorAll('input[type=file][data-preview]').forEach(inp => {
    inp.addEventListener('change', function () {
        const previewId = this.getAttribute('data-preview');
        const preview = document.getElementById(previewId);
        if (this.files && this.files[0] && preview) {
            preview.src = URL.createObjectURL(this.files[0]);
        }
    });
});
