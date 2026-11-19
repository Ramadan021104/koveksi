<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT pe.*, u.nama AS user_nama, u.email, u.no_hp, u.alamat,
                              p.nama AS produk_nama, p.satuan, p.harga_min,
                              k.nama AS kategori_nama
                       FROM pesanan pe
                       JOIN users u ON u.id=pe.id_user
                       JOIN produk p ON p.id=pe.id_produk
                       JOIN kategori k ON k.id=p.id_kategori
                       WHERE pe.id=?");
$stmt->execute([$id]);
$d = $stmt->fetch();
if (!$d) { die("Pesanan tidak ditemukan."); }

$harga_satuan = (int)$d['harga_min'];
$jumlah       = (int)$d['jumlah'];
$subtotal     = $harga_satuan * $jumlah;
$total        = (int)$d['total_harga'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan <?= e($d['kode']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#eef2f7; font-family: 'Inter', sans-serif; padding: 30px 0; }
        .struk {
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            padding: 40px 50px;
            border-radius: 6px;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
            border-top: 6px solid #0d4f8a;
        }
        .struk h1 { color:#0d4f8a; font-weight: 800; letter-spacing: 1px; margin: 0; }
        .struk .sub { color: #6c757d; font-size: 13px; }
        .label { color:#6c757d; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
        .value { font-weight: 600; color:#1f2937; }
        .divider { border-top: 2px dashed #cfd6e0; margin: 18px 0; }
        .total-box {
            background:#f5b400;
            color:#0a2540;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 800;
            display:flex; justify-content: space-between; align-items: center;
        }
        .ttd-box { margin-top: 50px; display:flex; justify-content: space-between; }
        .ttd-box .col { width: 40%; text-align: center; }
        .ttd-box .line { border-bottom: 1px solid #1f2937; margin-top: 60px; margin-bottom: 4px; }
        .actions { max-width: 720px; margin: 20px auto 0; display:flex; gap: 10px; justify-content: flex-end; }
        .status-badge { font-size: 12px; padding: 5px 12px; border-radius: 50px; font-weight: 600; }
        .status-Baru { background: #cfe2ff; color: #084298; }
        .status-Diproses { background: #fff3cd; color: #664d03; }
        .status-Selesai { background: #d1e7dd; color: #0f5132; }
        .status-Dibatalkan { background: #f8d7da; color: #842029; }

        @media print {
            body { background:#fff; padding: 0; }
            .actions { display: none !important; }
            .struk { box-shadow: none; border: 1px solid #ccc; max-width: 100%; }
        }
    </style>
</head>
<body>
<div class="actions">
    <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Print / Cetak</button>
    <a href="pesanan.php?id=<?= (int)$d['id'] ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="struk">

    <div class="d-flex justify-content-between align-items-start">
        <div class="d-flex gap-3 align-items-start">
            <img src="<?= LOGO_URL ?>" alt="Logo" style="width:72px; height:72px; border-radius:50%; object-fit:cover; border:2px solid #0d4f8a;">
            <div>
                <h1><?= e($pengaturan['nama_situs']) ?></h1>
                <div class="sub"><?= e($pengaturan['tagline']) ?></div>
                <div class="sub"><i class="bi bi-geo-alt"></i> <?= e($pengaturan['alamat']) ?></div>
                <div class="sub"><i class="bi bi-whatsapp"></i> +<?= e($pengaturan['wa_admin']) ?> &nbsp; <i class="bi bi-envelope"></i> <?= e($pengaturan['email_admin']) ?></div>
            </div>
        </div>
        <div class="text-end">
            <div class="label">No. Pesanan</div>
            <div class="value" style="font-size: 1.1rem; color:#0d4f8a;"><?= e($d['kode']) ?></div>
            <div class="mt-2"><span class="status-badge status-<?= e($d['status']) ?>"><?= e($d['status']) ?></span></div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="row mb-3">
        <div class="col-6">
            <div class="label">Tanggal Pesanan</div>
            <div class="value"><?= date('d M Y · H:i', strtotime($d['created_at'])) ?> WIB</div>
        </div>
        <div class="col-6 text-end">
            <div class="label">Estimasi Selesai</div>
            <div class="value text-success"><i class="bi bi-calendar-check"></i> <?= estimasi_selesai($d['created_at']) ?></div>
            <div class="sub">±<?= WAKTU_PENGERJAAN_HARI ?> hari pengerjaan</div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <div class="label">Dicetak Pada</div>
            <div class="value"><?= date('d M Y · H:i') ?> WIB</div>
        </div>
        <div class="col-6 text-end">
            <div class="label">No. Pesanan</div>
            <div class="value" style="color:#0d4f8a;"><?= e($d['kode']) ?></div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <div class="label">Pemesan</div>
            <div class="value"><?= e($d['user_nama']) ?></div>
            <div class="sub"><?= e($d['email']) ?></div>
            <div class="sub"><i class="bi bi-phone"></i> <?= e($d['no_hp'] ?: '-') ?></div>
        </div>
        <div class="col-6">
            <div class="label">Alamat Pengiriman</div>
            <div class="value" style="font-weight: 500;"><?= nl2br(e($d['alamat'] ?: '-')) ?></div>
        </div>
    </div>

    <div class="divider"></div>

    <h6 class="fw-bold mb-3" style="color:#0d4f8a">RINCIAN PESANAN</h6>
    <table class="table">
        <thead style="background:#0d4f8a; color:#fff;">
            <tr>
                <th>Produk</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="fw-bold"><?= e($d['produk_nama']) ?></div>
                    <div class="sub">Kategori: <?= e($d['kategori_nama']) ?></div>
                    <?php if ($d['bahan']):  ?><div class="sub">Bahan: <?= e($d['bahan']) ?></div><?php endif; ?>
                    <?php if ($d['ukuran']): ?><div class="sub">Ukuran: <?= e($d['ukuran']) ?></div><?php endif; ?>
                    <?php if ($d['warna']):  ?><div class="sub">Warna: <?= e($d['warna']) ?></div><?php endif; ?>
                </td>
                <td class="text-center"><?= $jumlah ?> <?= e($d['satuan']) ?></td>
                <td class="text-end"><?= rupiah($harga_satuan) ?></td>
                <td class="text-end fw-semibold"><?= rupiah($subtotal) ?></td>
            </tr>
        </tbody>
    </table>

    <?php if ($d['catatan']): ?>
        <div class="mb-3 p-3 rounded" style="background:#fff8e1; border-left: 4px solid #f5b400;">
            <div class="label mb-1">Catatan / Custom</div>
            <div><?= nl2br(e($d['catatan'])) ?></div>
        </div>
    <?php endif; ?>

    <div class="total-box">
        <span>TOTAL PEMBAYARAN</span>
        <span><?= rupiah($total) ?></span>
    </div>

    <div class="ttd-box">
        <div class="col">
            <div>Pemesan,</div>
            <div class="line"></div>
            <div class="fw-semibold">( <?= e($d['user_nama']) ?> )</div>
        </div>
        <div class="col">
            <div>Hormat Kami,</div>
            <div class="line"></div>
            <div class="fw-semibold">( Admin <?= e($pengaturan['nama_situs']) ?> )</div>
        </div>
    </div>

    <div class="divider"></div>
    <p class="text-center sub mb-0">
        Terima kasih atas kepercayaan Anda. Struk ini sah sebagai bukti pesanan.<br>
        <strong>Indra Konveksi</strong> · <?= date('Y') ?>
    </p>
</div>

<script>
    // Auto-print kalau URL ada ?print=1
    <?php if (!empty($_GET['print'])): ?>
    window.addEventListener('load', () => setTimeout(() => window.print(), 400));
    <?php endif; ?>
</script>
</body>
</html>
