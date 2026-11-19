<?php
$page_title = "Kontak";
require_once __DIR__ . '/includes/header.php';
?>
<h2 class="section-title">Hubungi Kami</h2>
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Informasi Kontak</h5>
                <p><i class="bi bi-geo-alt text-primary"></i> <strong>Alamat:</strong><br><?= e($pengaturan['alamat']) ?></p>
                <p><i class="bi bi-whatsapp text-success"></i> <strong>WhatsApp:</strong><br>+<?= e($pengaturan['wa_admin']) ?></p>
                <p><i class="bi bi-envelope text-primary"></i> <strong>Email:</strong><br><?= e($pengaturan['email_admin']) ?></p>
                <p><i class="bi bi-clock text-primary"></i> <strong>Jam Operasional:</strong><br>Senin - Sabtu, 08.00 - 17.00 WIB</p>
                <a href="https://wa.me/<?= e($pengaturan['wa_admin']) ?>" target="_blank" class="btn btn-success btn-lg w-100 mt-2">
                    <i class="bi bi-whatsapp"></i> Chat WhatsApp Sekarang
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Order Cepat</h5>
                <p class="text-muted">Untuk konsultasi & order langsung, klik salah satu tombol di bawah:</p>
                <div class="d-grid gap-2">
                    <a href="<?= BASE_URL ?>custom-order.php" class="btn btn-primary btn-lg"><i class="bi bi-pencil-square"></i> Form Custom Order</a>
                    <a href="<?= BASE_URL ?>katalog.php" class="btn btn-outline-primary btn-lg"><i class="bi bi-grid"></i> Lihat Katalog Lengkap</a>
                    <a href="mailto:<?= e($pengaturan['email_admin']) ?>" class="btn btn-outline-secondary"><i class="bi bi-envelope"></i> Email Kami</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
