<?php
$page_title = "Dashboard";
require_once __DIR__ . '/includes/header.php';

$total_produk = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$total_kategori = $pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
$total_user = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_pesanan = $pdo->query("SELECT COUNT(*) FROM pesanan")->fetchColumn();
$pesanan_baru = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE status='Baru'")->fetchColumn();
$total_omzet = $pdo->query("SELECT COALESCE(SUM(total_harga),0) FROM pesanan WHERE status='Selesai'")->fetchColumn();

$pesanan_terakhir = $pdo->query("SELECT pe.*, u.nama AS user_nama, p.nama AS produk_nama FROM pesanan pe JOIN users u ON u.id=pe.id_user JOIN produk p ON p.id=pe.id_produk ORDER BY pe.id DESC LIMIT 5")->fetchAll();
?>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="lbl">Total Produk</div>
            <div class="num"><?= $total_produk ?></div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card" style="border-left-color:#f5b400">
            <div class="lbl">Kategori</div>
            <div class="num"><?= $total_kategori ?></div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card" style="border-left-color:#198754">
            <div class="lbl">Customer</div>
            <div class="num"><?= $total_user ?></div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card" style="border-left-color:#dc3545">
            <div class="lbl">Total Pesanan</div>
            <div class="num"><?= $total_pesanan ?></div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card" style="border-left-color:#0dcaf0">
            <div class="lbl">Pesanan Baru</div>
            <div class="num"><?= $pesanan_baru ?></div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card" style="border-left-color:#6610f2">
            <div class="lbl">Omzet Selesai</div>
            <div class="num" style="font-size:1.1rem"><?= rupiah($total_omzet) ?></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">5 Pesanan Terakhir</h6>
        <a href="pesanan.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Kode</th><th>Customer</th><th>Produk</th><th>Jumlah</th><th>Total</th><th>Status</th><th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$pesanan_terakhir): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                <?php else: foreach ($pesanan_terakhir as $p): ?>
                    <tr>
                        <td><a href="pesanan.php?id=<?= (int)$p['id'] ?>" class="fw-semibold"><?= e($p['kode']) ?></a></td>
                        <td><?= e($p['user_nama']) ?></td>
                        <td><?= e($p['produk_nama']) ?></td>
                        <td><?= (int)$p['jumlah'] ?></td>
                        <td><?= rupiah($p['total_harga']) ?></td>
                        <td><span class="status-badge status-<?= e($p['status']) ?>"><?= e($p['status']) ?></span></td>
                        <td class="small text-muted"><?= date('d M Y H:i', strtotime($p['created_at'])) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
