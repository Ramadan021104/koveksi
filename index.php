<?php
$page_title = "Beranda";
require_once __DIR__ . '/includes/header.php';

$slides   = require __DIR__ . '/config/slides.php';
$kategori = $pdo->query("SELECT * FROM kategori ORDER BY id ASC")->fetchAll();
$produk_unggulan = $pdo->query("SELECT p.*, k.nama AS kategori_nama, k.slug AS kategori_slug FROM produk p JOIN kategori k ON k.id=p.id_kategori ORDER BY RAND() LIMIT 8")->fetchAll();
?>

<!-- ============== HERO CAROUSEL / SLIDER PROMO ==============
     Edit slide di file: config/slides.php (programmer only).
     Ganti gambar di folder: assets/images/slides/
============================================================= -->
<section class="hero-carousel-wrap mb-5">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <?php foreach ($slides as $i => $s): ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>" <?= $i===0?'class="active" aria-current="true"':'' ?>></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($slides as $i => $s):
                $img_url = BASE_URL . 'assets/images/slides/' . $s['gambar'];
            ?>
                <div class="carousel-item <?= $i===0?'active':'' ?>">
                   <div class="hero-slide"
                   style="background: url('<?= e($img_url) ?>') center center / cover no-repeat;">
                        <div class="container">
                            <div class="hero-slide-content">
                                <span class="badge bg-warning text-dark mb-3 px-3 py-2 fw-semibold"></span>
                                <h1 class="hero-title"><?= e($s['judul']) ?></h1>
                                <p class="hero-subtitle"><?= e($s['subjudul']) ?></p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<section class="mb-5">
    <h3 class="section-title">Kategori Produk</h3>
    <div class="row g-3">
        <?php
        $icons = ['topi'=>'bi-cap', 'id-card'=>'bi-credit-card-2-front', 'lanyard'=>'bi-tag', 'almamater'=>'bi-mortarboard',
                  'rompi-vest'=>'bi-vest', 'kaos'=>'bi-person-arms-up', 'seragam-sekolah'=>'bi-backpack',
                  'jaket'=>'bi-snow', 'pdh'=>'bi-briefcase', 'batik'=>'bi-palette', 'seragam-dinas'=>'bi-shield-shaded'];
        foreach ($kategori as $k):
            $icon = $icons[$k['slug']] ?? 'bi-box';
        ?>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="<?= BASE_URL ?>katalog.php?kategori=<?= e($k['slug']) ?>" class="kategori-card">
                    <i class="bi <?= $icon ?>"></i>
                    <div class="fw-semibold"><?= e($k['nama']) ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h3 class="section-title mb-0">Produk Unggulan</h3>
        <a href="<?= BASE_URL ?>katalog.php" class="text-primary text-decoration-none fw-semibold">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3">
        <?php foreach ($produk_unggulan as $p): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card position-relative">
                    <span class="badge-kategori"><?= e($p['kategori_nama']) ?></span>
                    <img src="<?= gambar_produk($p['gambar']) ?>" class="card-img-top" alt="<?= e($p['nama']) ?>">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1 text-truncate" title="<?= e($p['nama']) ?>"><?= e($p['nama']) ?></h6>
                        <p class="small text-muted mb-2"><?= e($p['bahan']) ?></p>
                        <p class="harga mb-2"><?= harga_range($p['harga_min'], $p['harga_max'], $p['satuan']) ?></p>
                        <a href="<?= BASE_URL ?>produk.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-primary w-100">Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-white p-4 p-md-5 rounded-4 shadow-sm mb-4">
    <div class="row align-items-center g-4">
        <div class="col-md-8">
            <h3 class="fw-bold text-primary mb-2">Punya desain sendiri? Yuk Custom Order!</h3>
            <p class="text-muted mb-0">Upload desain Anda, pilih bahan dan ukuran, lalu kami akan proses pesanan dengan kualitas terbaik. Min order mulai 12 pcs. Diskon untuk order banyak!</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?= BASE_URL ?>custom-order.php" class="btn btn-primary btn-lg"><i class="bi bi-pencil-square"></i> Mulai Custom</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
