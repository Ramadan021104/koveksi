<?php
$page_title = "Pesanan Customer";
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    if (in_array($status, ['Baru','Diproses','Selesai','Dibatalkan'])) {
        $pdo->prepare("UPDATE pesanan SET status=? WHERE id=?")->execute([$status, $id]);
        flash('ok', 'Status pesanan diperbarui.', 'success');
    }
    redirect('pesanan.php' . ($id ? '?id=' . $id : ''));
}

$id            = (int)($_GET['id'] ?? 0);
$filter_status = $_GET['status'] ?? '';
$q             = trim($_GET['q'] ?? '');

if ($id) {
    // ===== DETAIL PESANAN =====
    $stmt = $pdo->prepare("SELECT pe.*, u.nama AS user_nama, u.email, u.no_hp, u.alamat,
                                  p.nama AS produk_nama, p.satuan, k.nama AS kategori_nama
                           FROM pesanan pe
                           JOIN users u ON u.id=pe.id_user
                           JOIN produk p ON p.id=pe.id_produk
                           JOIN kategori k ON k.id=p.id_kategori
                           WHERE pe.id=?");
    $stmt->execute([$id]);
    $detail = $stmt->fetch();
    if (!$detail) { flash('err', 'Pesanan tidak ditemukan.', 'danger'); redirect('pesanan.php'); }

    $wa_link = $detail['no_hp'] ? wa_link_struk($detail['no_hp'], $detail, 'customer') : null;
?>
    <a href="pesanan.php" class="btn btn-link ps-0 mb-2">← Kembali ke daftar</a>
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="fw-bold mb-0">Pesanan <?= e($detail['kode']) ?></h5>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="status-badge status-<?= e($detail['status']) ?>"><?= e($detail['status']) ?></span>
                            <a href="struk-print.php?id=<?= (int)$detail['id'] ?>" target="_blank" class="btn btn-sm btn-dark"><i class="bi bi-printer"></i> Print Struk</a>
                        </div>
                    </div>
                    <table class="table table-sm">
                        <tr><td class="text-muted" style="width:140px">Tanggal</td><td><?= date('d M Y H:i', strtotime($detail['created_at'])) ?></td></tr>
                        <tr><td class="text-muted">Estimasi Selesai</td>
                            <td class="fw-semibold text-success" data-testid="admin-estimasi-selesai">
                                <i class="bi bi-calendar-check"></i> <?= estimasi_selesai($detail['created_at']) ?>
                                <span class="badge bg-success bg-opacity-10 text-success ms-2">±<?= WAKTU_PENGERJAAN_HARI ?> hari pengerjaan</span>
                            </td></tr>
                        <tr><td class="text-muted">Kategori</td><td><?= e($detail['kategori_nama']) ?></td></tr>
                        <tr><td class="text-muted">Produk</td><td><strong><?= e($detail['produk_nama']) ?></strong></td></tr>
                        <tr><td class="text-muted">Jumlah</td><td><?= (int)$detail['jumlah'] ?> <?= e($detail['satuan']) ?></td></tr>
                        <tr><td class="text-muted">Bahan</td><td><?= e($detail['bahan']?:'-') ?></td></tr>
                        <tr><td class="text-muted">Ukuran</td><td><?= e($detail['ukuran']?:'-') ?></td></tr>
                        <tr><td class="text-muted">Warna</td><td><?= e($detail['warna']?:'-') ?></td></tr>
                        <tr><td class="text-muted">Catatan</td><td><?= nl2br(e($detail['catatan']?:'-')) ?></td></tr>
                        <tr><td class="text-muted">File Desain</td><td>
                            <?php if ($detail['file_desain']): ?>
                                <a href="<?= UPLOAD_URL . e($detail['file_desain']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Download</a>
                            <?php else: ?>-<?php endif; ?>
                        </td></tr>
                        <tr><td class="text-muted">Total Harga</td><td class="fw-bold text-primary fs-5"><?= rupiah($detail['total_harga']) ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-receipt"></i> Preview Struk WhatsApp ke Customer</h6>
                    <pre class="bg-light p-3 rounded small mb-3" style="white-space:pre-wrap; font-family: 'Courier New', monospace;"><?= e(format_struk_wa($detail, 'customer')) ?></pre>
                    <?php if ($wa_link): ?>
                        <a href="<?= e($wa_link) ?>" target="_blank" class="btn btn-success w-100"><i class="bi bi-whatsapp"></i> Kirim Struk ini ke Customer (WA)</a>
                    <?php else: ?>
                        <div class="alert alert-warning small mb-0">Customer belum mengisi nomor HP, tidak bisa kirim WA.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold">Data Customer</h6>
                    <p class="mb-1"><strong><?= e($detail['user_nama']) ?></strong></p>
                    <p class="small text-muted mb-1"><i class="bi bi-envelope"></i> <?= e($detail['email']) ?></p>
                    <p class="small text-muted mb-1"><i class="bi bi-phone"></i> <?= e($detail['no_hp']?:'-') ?></p>
                    <p class="small text-muted"><i class="bi bi-geo-alt"></i> <?= e($detail['alamat']?:'-') ?></p>
                </div>
            </div>
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold">Update Status</h6>
                    <form method="POST">
                    <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int)$detail['id'] ?>">
                        <select name="status" class="form-select mb-2">
                            <?php foreach (['Baru','Diproses','Selesai','Dibatalkan'] as $s): ?>
                                <option value="<?= $s ?>" <?= $detail['status']==$s?'selected':'' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button name="update_status" value="1" class="btn btn-primary w-100"><i class="bi bi-arrow-clockwise"></i> Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    // ===== LIST PESANAN + SEARCH =====
    $sql = "SELECT pe.*, u.nama AS user_nama, p.nama AS produk_nama, k.nama AS kategori_nama
            FROM pesanan pe
            JOIN users u ON u.id=pe.id_user
            JOIN produk p ON p.id=pe.id_produk
            JOIN kategori k ON k.id=p.id_kategori
            WHERE 1=1";
    $params = [];
    if ($filter_status) { $sql .= " AND pe.status=?"; $params[] = $filter_status; }
    if ($q) {
        $sql .= " AND (pe.kode LIKE ? OR u.nama LIKE ? OR u.email LIKE ? OR p.nama LIKE ? OR k.nama LIKE ?)";
        $like = "%$q%";
        array_push($params, $like, $like, $like, $like, $like);
    }
    $sql .= " ORDER BY pe.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $pesanan = $stmt->fetchAll();
?>
    <form method="GET" class="row g-2 mb-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label small mb-1">Cari (kode, nama customer, produk, kategori)</label>
            <input type="search" name="q" value="<?= e($q) ?>" class="form-control" placeholder="Cth: IK-2026, Customer Demo, Topi, Kaos...">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <?php foreach (['Baru','Diproses','Selesai','Dibatalkan'] as $s): ?>
                    <option value="<?= $s ?>" <?= $filter_status==$s?'selected':'' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
        </div>
        <div class="col-md-3 text-md-end">
            <a href="export-pesanan.php?<?= http_build_query(['q'=>$q,'status'=>$filter_status]) ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Export ke Excel
            </a>
            <?php if ($q || $filter_status): ?>
                <a href="pesanan.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-circle"></i> Reset</a>
            <?php endif; ?>
        </div>
    </form>
    <p class="text-muted small">Total: <strong><?= count($pesanan) ?></strong> pesanan<?= $q ? ' untuk pencarian "'.e($q).'"' : '' ?></p>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Kode</th><th>Tanggal</th><th>Customer</th><th>Produk</th><th>Jumlah</th><th>Total</th><th>Est. Selesai</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if (!$pesanan): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada pesanan ditemukan.</td></tr>
                    <?php else: foreach ($pesanan as $p): ?>
                        <tr>
                            <td class="fw-semibold"><?= e($p['kode']) ?></td>
                            <td class="small"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                            <td><?= e($p['user_nama']) ?></td>
                            <td>
                                <div><?= e($p['produk_nama']) ?></div>
                                <small class="text-muted"><?= e($p['kategori_nama']) ?></small>
                            </td>
                            <td><?= (int)$p['jumlah'] ?></td>
                            <td><?= rupiah($p['total_harga']) ?></td>
                            <td class="small text-success fw-semibold"><?= estimasi_selesai($p['created_at']) ?></td>
                            <td><span class="status-badge status-<?= e($p['status']) ?>"><?= e($p['status']) ?></span></td>
                            <td><a href="?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Detail</a>
                                <a href="struk-print.php?id=<?= (int)$p['id'] ?>" target="_blank" class="btn btn-sm btn-dark" title="Print Struk"><i class="bi bi-printer"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
