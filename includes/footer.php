    </div>
</main>
<footer class="bg-dark text-light pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <img src="<?= LOGO_URL ?>" alt="Logo" style="width:56px; height:56px; border-radius:50%; object-fit:cover; background:#fff; padding:2px;">
                    <h5 class="fw-bold text-warning mb-0"><?= e($pengaturan['nama_situs']) ?></h5>
                </div>
                <p class="small text-secondary"><?= e($pengaturan['tagline']) ?></p>
                <p class="small mb-0"><i class="bi bi-geo-alt"></i> <?= e($pengaturan['alamat']) ?></p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold">Kategori Produk</h6>
                <ul class="list-unstyled small">
                    <?php foreach (array_slice($kategori_nav, 0, 6) as $k): ?>
                        <li><a href="<?= BASE_URL ?>katalog.php?kategori=<?= e($k['slug']) ?>" class="text-secondary text-decoration-none"><?= e($k['nama']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold">Kontak Kami</h6>
                <p class="small mb-1"><i class="bi bi-whatsapp"></i> +<?= e($pengaturan['wa_admin']) ?></p>
                <p class="small mb-1"><i class="bi bi-envelope"></i> <?= e($pengaturan['email_admin']) ?></p>
                <a href="https://wa.me/<?= e($pengaturan['wa_admin']) ?>" target="_blank" class="btn btn-success btn-sm mt-2">
                    <i class="bi bi-whatsapp"></i> Chat WhatsApp
                </a>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center small mb-0 text-secondary">© <?= date('Y') ?> <?= e($pengaturan['nama_situs']) ?>. All rights reserved.</p>
    </div>
</footer>
<a href="https://wa.me/<?= e($pengaturan['wa_admin']) ?>" target="_blank" class="wa-float">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Tombol Install Aplikasi (PWA) -->
<button id="pwaInstallBtn" class="pwa-install-btn" type="button">
    <i class="bi bi-download"></i> Install Aplikasi
</button>

<!-- Modal petunjuk install khusus iOS/Safari (tidak support beforeinstallprompt) -->
<div class="modal fade" id="iosInstallModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-download"></i> Install Aplikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Untuk menginstall aplikasi <?= e($pengaturan['nama_situs']) ?> di iPhone/iPad, ikuti langkah berikut:</p>
                <ol class="mb-0">
                    <li class="mb-2">Ketuk tombol <strong>Share</strong> <i class="bi bi-box-arrow-up"></i> di browser Safari</li>
                    <li class="mb-2">Pilih <strong>"Add to Home Screen"</strong> / <strong>"Tambah ke Layar Utama"</strong></li>
                    <li>Ketuk <strong>"Add"</strong> / <strong>"Tambah"</strong> di pojok kanan atas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/script.js"></script>
<script src="<?= BASE_URL ?>assets/js/pwa-install.js"></script>
</body>
</html>
