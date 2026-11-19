<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$user = user_data();
$stmt = $pdo->prepare("SELECT pe.*, p.nama AS produk_nama, p.satuan, k.nama AS kategori_nama
                       FROM pesanan pe
                       JOIN produk p ON p.id=pe.id_produk
                       JOIN kategori k ON k.id=p.id_kategori
                       WHERE pe.id_user=?
                       ORDER BY pe.id DESC");
$stmt->execute([$user['id']]);
$pesanan = $stmt->fetchAll();

// Tambahkan info user ke setiap row supaya helper struk WA bisa pakai
foreach ($pesanan as &$row) {
    $row['user_nama'] = $user['nama'];
}
unset($row);

$id_baru = (int)($_GET['baru'] ?? 0);
$last_wa = $_SESSION['last_wa'] ?? null;
unset($_SESSION['last_wa']);

$page_title = "Pesanan Saya";
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($id_baru && $last_wa): ?>
<div class="alert alert-success d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <i class="bi bi-check-circle-fill"></i> <strong>Pesanan berhasil dibuat!</strong>
        Klik tombol berikut untuk konfirmasi pembelian via WhatsApp ke admin (struk pesanan otomatis terlampir).
    </div>
    <a href="<?= e($last_wa) ?>" target="_blank" class="btn btn-success"><i class="bi bi-whatsapp"></i> Kirim Struk ke Admin</a>
</div>
<?php endif; ?>

<h2 class="section-title">Pesanan Saya</h2>

<?php if (!$pesanan): ?>
    <div class="alert alert-info">Belum ada pesanan. <a href="<?= BASE_URL ?>custom-order.php">Silahkan Buat pesanan baru</a>.</div>
<?php else: ?>
<div class="table-responsive bg-white rounded-3 shadow-sm">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Estimasi Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pesanan as $p): ?>
                <tr>
                    <td class="fw-semibold"><?= e($p['kode']) ?></td>
                    <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                    <td>
                        <div><?= e($p['produk_nama']) ?></div>
                        <small class="text-muted"><?= e($p['kategori_nama']) ?></small>
                    </td>
                    <td><?= (int)$p['jumlah'] ?> <?= e($p['satuan']) ?></td>
                    <td><?= rupiah($p['total_harga']) ?></td>
                    <td class="small">
                        <span class="fw-semibold text-success" data-testid="estimasi-selesai-<?= e($p['kode']) ?>">
                            <i class="bi bi-calendar-check"></i> <?= estimasi_selesai($p['created_at']) ?>
                        </span>
                        <br><span class="text-muted" style="font-size:11px">±<?= WAKTU_PENGERJAAN_HARI ?> hari</span>
                    </td>
                    <td><span class="status-badge status-<?= e($p['status']) ?>"><?= e($p['status']) ?></span></td>
                    <td>
                        <a href="<?= e(wa_link_struk($pengaturan['wa_admin'], $p, 'admin')) ?>"
                           target="_blank"
                           class="btn btn-sm btn-success" title="Kirim struk pesanan ke admin">
                            <i class="bi bi-whatsapp"></i> Kirim ke Admin
                        </a>
                    </td>
                </tr>
                <?php if ($p['catatan'] || $p['file_desain'] || $p['bahan'] || $p['ukuran'] || $p['warna']): ?>
                    <tr class="bg-light">
                        <td colspan="8" class="small">
                            <strong>Detail Pesanan:</strong>
                            Bahan: <?= e($p['bahan']?:'-') ?> | Ukuran: <?= e($p['ukuran']?:'-') ?> | Warna: <?= e($p['warna']?:'-') ?>
                            <?php if ($p['catatan']): ?><br><strong>Catatan:</strong> <?= e($p['catatan']) ?><?php endif; ?>
                            <?php if ($p['file_desain']): ?>
                                <br><strong>File desain:</strong> <a href="<?= UPLOAD_URL . e($p['file_desain']) ?>" target="_blank">Lihat file</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="alert alert-info small mt-3">
    <i class="bi bi-info-circle"></i> Tombol <strong>Kirim ke Admin</strong> akan membuka WhatsApp dengan struk pesanan lengkap (produk, jumlah, bahan, ukuran, warna, catatan & total) sudah otomatis terisi — kamu tinggal kirim.
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
