<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, k.nama AS kategori_nama FROM produk p JOIN kategori k ON k.id=p.id_kategori WHERE p.id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) {
    flash('err', 'Produk tidak ditemukan', 'danger');
    redirect(BASE_URL . 'katalog.php');
}
$page_title = $p['nama'];
require_once __DIR__ . '/includes/header.php';
?>

<nav aria-label="breadcrumb" class="small">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Beranda</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>katalog.php">Katalog</a></li>
        <li class="breadcrumb-item active"><?= e($p['nama']) ?></li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-5">
        <?php
        $galeri = $pdo->prepare("SELECT * FROM produk_gambar WHERE id_produk=? ORDER BY urutan");
        $galeri->execute([$p['id']]);
        $imgs = $galeri->fetchAll();
        $all_imgs = [['gambar' => $p['gambar']]];
        foreach ($imgs as $gi) $all_imgs[] = ['gambar' => $gi['gambar']];
        ?>
        <?php if (count($all_imgs) > 1): ?>
            <div id="produkGallery" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded-3 shadow-sm">
                    <?php foreach ($all_imgs as $i => $g): ?>
                        <div class="carousel-item <?= $i===0?'active':'' ?>">
                            <img src="<?= gambar_produk($g['gambar']) ?>" class="d-block w-100" style="height:420px; object-fit:cover; background:#eef3f9;">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#produkGallery" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#produkGallery" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>
            <div class="d-flex gap-2 mt-2 flex-wrap">
                <?php foreach ($all_imgs as $i => $g): ?>
                    <button type="button" data-bs-target="#produkGallery" data-bs-slide-to="<?= $i ?>" class="btn p-0 border" style="width:60px; height:60px;">
                        <img src="<?= gambar_produk($g['gambar']) ?>" class="w-100 h-100 rounded" style="object-fit:cover;">
                    </button>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <img src="<?= gambar_produk($p['gambar']) ?>" class="detail-img" alt="<?= e($p['nama']) ?>">
        <?php endif; ?>
    </div>
    <div class="col-md-7">
        <span class="badge bg-primary"><?= e($p['kategori_nama']) ?></span>
        <h2 class="fw-bold mt-2"><?= e($p['nama']) ?></h2>
        <h4 class="text-primary fw-bold mb-3"><?= harga_range($p['harga_min'], $p['harga_max'], $p['satuan']) ?></h4>

        <table class="table table-sm">
            <tr><td class="text-muted" style="width:120px">Bahan</td><td><strong><?= e($p['bahan'] ?: '-') ?></strong></td></tr>
            <tr><td class="text-muted">Kategori</td><td><?= e($p['kategori_nama']) ?></td></tr>
            <tr><td class="text-muted">Satuan</td><td><?= e($p['satuan']) ?></td></tr>
            <tr><td class="text-muted">Status</td><td><?= $p['stok_tersedia'] ? '<span class="badge bg-success">Tersedia</span>' : '<span class="badge bg-secondary">Habis</span>' ?></td></tr>
        </table>

        <h6 class="fw-bold mt-3">Deskripsi</h6>
        <p class="text-muted"><?= nl2br(e($p['deskripsi'])) ?></p>

        <?php if ($p['keterangan']): ?>
            <div class="alert alert-warning small"><i class="bi bi-info-circle"></i> <?= nl2br(e($p['keterangan'])) ?></div>
        <?php endif; ?>

        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>custom-order.php?id_produk=<?= (int)$p['id'] ?>" class="btn btn-primary btn-lg flex-fill">
                <i class="bi bi-pencil-square"></i> Pesan / Custom Order
            </a>
            <a href="https://wa.me/<?= e($pengaturan['wa_admin']) ?>?text=<?= urlencode('Halo, saya ingin tanya produk: ' . $p['nama']) ?>" target="_blank" class="btn btn-success btn-lg">
                <i class="bi bi-whatsapp"></i>
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
