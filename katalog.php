<?php
$page_title = "Katalog Produk";
require_once __DIR__ . '/includes/header.php';

$slug_kategori = $_GET['kategori'] ?? '';
$cari = trim($_GET['q'] ?? '');

$sql = "SELECT p.*, k.nama AS kategori_nama, k.slug AS kategori_slug FROM produk p JOIN kategori k ON k.id=p.id_kategori WHERE 1=1";
$params = [];
if ($slug_kategori) {
    $sql .= " AND k.slug = ?";
    $params[] = $slug_kategori;
}
if ($cari) {
    $sql .= " AND (p.nama LIKE ? OR p.bahan LIKE ?)";
    $params[] = "%$cari%";
    $params[] = "%$cari%";
}
$sql .= " ORDER BY p.id_kategori, p.id";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produk = $stmt->fetchAll();

$kategori_aktif = null;
if ($slug_kategori) {
    foreach ($kategori_nav as $k) {
        if ($k['slug'] === $slug_kategori) { $kategori_aktif = $k; break; }
    }
}
?>

<h2 class="section-title">
    <?= $kategori_aktif ? e($kategori_aktif['nama']) : 'Katalog Produk' ?>
</h2>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <select name="kategori" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            <?php foreach ($kategori_nav as $k): ?>
                <option value="<?= e($k['slug']) ?>" <?= $slug_kategori==$k['slug']?'selected':'' ?>><?= e($k['nama']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <input type="text" name="q" value="<?= e($cari) ?>" class="form-control" placeholder="Cari nama produk / bahan...">
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
    </div>
</form>

<?php if (!$produk): ?>
    <div class="alert alert-info">Produk tidak ditemukan.</div>
<?php else: ?>
    <p class="text-muted">Menampilkan <?= count($produk) ?> produk.</p>
    <div class="row g-3">
        <?php foreach ($produk as $p): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card position-relative">
                    <span class="badge-kategori"><?= e($p['kategori_nama']) ?></span>
                    <img src="<?= gambar_produk($p['gambar']) ?>" class="card-img-top" alt="<?= e($p['nama']) ?>">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1" title="<?= e($p['nama']) ?>"><?= e($p['nama']) ?></h6>
                        <p class="small text-muted mb-2"><?= e($p['bahan']) ?></p>
                        <p class="harga mb-2"><?= harga_range($p['harga_min'], $p['harga_max'], $p['satuan']) ?></p>
                        <a href="<?= BASE_URL ?>produk.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-primary w-100">Detail & Order</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
