<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$produk_list = $pdo->query("SELECT p.id, p.nama, p.harga_min, p.bahan, k.nama AS kategori_nama FROM produk p JOIN kategori k ON k.id=p.id_kategori ORDER BY k.nama, p.nama")->fetchAll();
$id_produk_default = (int)($_GET['id_produk'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = (int)($_POST['id_produk'] ?? 0);
    $bahan     = trim($_POST['bahan'] ?? '');
    $warna     = trim($_POST['warna'] ?? '');
    $catatan   = trim($_POST['catatan'] ?? '');

    // Hitung ukuran detail (per size)
    $sizes_input = $_POST['size'] ?? [];
    $ukuran_arr = [];
    $jumlah     = 0;
    foreach ($sizes_input as $label => $val) {
        $n = max(0, (int)$val);
        if ($n > 0) {
            $ukuran_arr[] = "$label: $n";
            $jumlah += $n;
        }
    }
    $ukuran = implode(', ', $ukuran_arr);

    $stmt = $pdo->prepare("SELECT p.*, k.nama AS kategori_nama FROM produk p JOIN kategori k ON k.id=p.id_kategori WHERE p.id=?");
    $stmt->execute([$id_produk]);
    $produk = $stmt->fetch();

    if (!$produk) {
        flash('order', 'Produk tidak valid.', 'danger');
    } elseif ($jumlah < 1) {
        flash('order', 'Mohon isi jumlah ukuran minimal 1 pcs di salah satu size.', 'danger');
    } else {
        $file = upload_file('file_desain', 'desain');
        $total = $produk['harga_min'] * $jumlah;
        $kode = kode_pesanan();
        $user = user_data();

        $ins = $pdo->prepare("INSERT INTO pesanan (kode, id_user, id_produk, jumlah, bahan, ukuran, warna, catatan, file_desain, total_harga, status) VALUES (?,?,?,?,?,?,?,?,?,?, 'Baru')");
        $ins->execute([$kode, $user['id'], $id_produk, $jumlah, $bahan, $ukuran, $warna, $catatan, $file, $total]);
        $id_pesanan = $pdo->lastInsertId();

        // Email notifikasi (di-skip di localhost)
        $body = "<h3>Pesanan Baru: $kode</h3>"
              . "<p>Customer: " . e($user['nama']) . " ({$user['email']})</p>"
              . "<p>Produk: " . e($produk['nama']) . " x $jumlah</p>"
              . "<p>Total: " . rupiah($total) . "</p>";
        kirim_email_order($pengaturan['email_admin'], "Pesanan Baru $kode", $body);

        // Build WA link dengan struk lengkap pre-filled
        $detail_struk = [
            'kode'          => $kode,
            'created_at'    => date('Y-m-d H:i:s'),
            'status'        => 'Baru',
            'user_nama'     => $user['nama'],
            'kategori_nama' => $produk['kategori_nama'],
            'produk_nama'   => $produk['nama'],
            'jumlah'        => $jumlah,
            'satuan'        => $produk['satuan'],
            'bahan'         => $bahan,
            'ukuran'        => $ukuran,
            'warna'         => $warna,
            'catatan'       => $catatan,
            'file_desain'   => $file,
            'total_harga'   => $total,
        ];
        $_SESSION['last_wa'] = wa_link_struk($pengaturan['wa_admin'], $detail_struk, 'admin');

        // Notifikasi WhatsApp otomatis ke admin (kalau API aktif)
        $struk_text = format_struk_wa($detail_struk, 'admin');
        kirim_wa_admin($struk_text);

        flash('order', 'Pesanan #' . $kode . ' berhasil dibuat! Anda akan diarahkan ke WhatsApp admin.', 'success');
        redirect(BASE_URL . 'pesanan-saya.php?baru=' . $id_pesanan);
    }
}

$page_title = "Custom Order";
require_once __DIR__ . '/includes/header.php';
?>

<h2 class="section-title">Custom Order</h2>
<p class="text-muted">Lengkapi form di bawah untuk membuat pesanan custom. Setelah submit, Anda akan otomatis dapat link WhatsApp dengan <strong>struk pesanan lengkap</strong> yang tinggal dikirim ke admin.</p>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Produk *</label>
                        <select name="id_produk" class="form-select" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($produk_list as $pl): ?>
                                <option value="<?= (int)$pl['id'] ?>" <?= $id_produk_default==$pl['id']?'selected':'' ?>>
                                    [<?= e($pl['kategori_nama']) ?>] <?= e($pl['nama']) ?> – mulai <?= rupiah($pl['harga_min']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bahan</label>
                            <input type="text" name="bahan" class="form-control" placeholder="Cth: Drill, Cotton 30S">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Warna</label>
                            <input type="text" name="warna" class="form-control" placeholder="Cth: Hitam, Biru Dongker">
                        </div>
                    </div>

                    <div class="mt-3 p-3 rounded" style="background:#f5f8fc; border:1px solid #cfdbe8;">
                        <label class="form-label fw-semibold mb-2">
                            <i class="bi bi-rulers text-primary"></i> Detail Ukuran *
                            <span class="small text-muted fw-normal">(isi jumlah di setiap ukuran yang dipesan)</span>
                        </label>
                        <div class="row g-2" id="sizeGrid">
                            <?php foreach (['S','M','L','XL','XXL','XXXL'] as $sz): ?>
                                <div class="col-6 col-md-2">
                                    <label class="form-label small mb-1 fw-bold text-center d-block bg-primary text-white py-1 rounded-top"><?= $sz ?></label>
                                    <input type="number" name="size[<?= $sz ?>]" min="0" value="0" class="form-control text-center size-input" style="border-top-left-radius:0; border-top-right-radius:0;">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center p-2 bg-warning rounded">
                            <span class="fw-bold text-dark">TOTAL JUMLAH:</span>
                            <span class="fw-bold fs-5 text-dark"><span id="totalJumlah">0</span> pcs</span>
                        </div>
                        <div class="form-text mt-2">
                            <i class="bi bi-info-circle"></i> Min total 12 pcs untuk harga normal.
                            Bisa juga input ukuran custom di kolom Catatan jika butuh size lain (mis: XS, anak-anak, jumbo).
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan / Detail Custom</label>
                        <textarea name="catatan" class="form-control" rows="4" placeholder="Cth: Sablon logo di depan, bordir nama di lengan, dll."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Desain (opsional)</label>
                        <input type="file" name="file_desain" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        <div class="form-text">Max 5MB. Format: JPG, PNG, PDF.</div>
                    </div>
                    <div class="alert alert-info d-flex align-items-center gap-3 mb-3" data-testid="info-waktu-pengerjaan">
                        <i class="bi bi-clock-history fs-3"></i>
                        <div>
                            <strong>Estimasi Waktu Pengerjaan: 1 Bulan (±<?= WAKTU_PENGERJAAN_HARI ?> hari kerja)</strong><br>
                            <small>Setelah pesanan dikonfirmasi & DP diterima, pesanan akan diproses dan diselesaikan dalam waktu maksimal 30 hari. Estimasi tanggal selesai akan ditampilkan otomatis di halaman <em>Pesanan Saya</em>.</small>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-lg w-100"><i class="bi bi-send"></i> Kirim Pesanan & Buat Struk</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card bg-light border-0">
            <div class="card-body">
                <h6 class="fw-bold"><i class="bi bi-info-circle text-primary"></i> Cara Order</h6>
                <ol class="small text-muted ps-3 mb-0">
                    <li>Pilih produk dan isi detail.</li>
                    <li>Upload desain (jika ada).</li>
                    <li>Submit form → otomatis dibuatkan <strong>struk pesanan</strong>.</li>
                    <li>Klik tombol "Kirim Struk ke Admin" → buka WhatsApp dengan struk lengkap sudah pre-filled.</li>
                    <li>Tinggal kirim ke admin, tunggu konfirmasi pembayaran.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
(function() {
    const inputs = document.querySelectorAll('.size-input');
    const total = document.getElementById('totalJumlah');
    function recalc() {
        let sum = 0;
        inputs.forEach(i => sum += parseInt(i.value) || 0);
        if (total) total.textContent = sum;
    }
    inputs.forEach(i => i.addEventListener('input', recalc));
    recalc();
})();
</script>
