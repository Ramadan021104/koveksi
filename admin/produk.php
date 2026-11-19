<?php
$page_title = "Produk";
require_once __DIR__ . '/includes/header.php';

$aksi = $_GET['aksi'] ?? '';
$id   = (int)($_GET['id'] ?? 0);

if ($aksi === 'hapus' && $id) {
    $pdo->prepare("DELETE FROM produk WHERE id=?")->execute([$id]);
    flash('ok', 'Produk dihapus.', 'success');
    redirect('produk.php');
}

// Hapus gambar galeri tertentu
if (($_GET['hapus_gambar'] ?? 0) > 0) {
    $img_id = (int)$_GET['hapus_gambar'];
    $row = $pdo->query("SELECT id_produk, gambar FROM produk_gambar WHERE id=$img_id")->fetch();
    if ($row) {
        $p = UPLOAD_PATH . $row['gambar'];
        if (file_exists($p)) @unlink($p);
        $pdo->prepare("DELETE FROM produk_gambar WHERE id=?")->execute([$img_id]);
        redirect('produk.php?aksi=edit&id=' . $row['id_produk']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_kategori' => (int)$_POST['id_kategori'],
        'nama'        => trim($_POST['nama']),
        'bahan'       => trim($_POST['bahan']),
        'harga_min'   => (int)$_POST['harga_min'],
        'harga_max'   => $_POST['harga_max'] !== '' ? (int)$_POST['harga_max'] : null,
        'satuan'      => trim($_POST['satuan']),
        'deskripsi'   => trim($_POST['deskripsi']),
        'keterangan'  => trim($_POST['keterangan']),
        'stok_tersedia' => isset($_POST['stok_tersedia']) ? 1 : 0,
    ];

    $gambar = upload_file('gambar', 'produk');
    if ($id) {
        $sql = "UPDATE produk SET id_kategori=:id_kategori, nama=:nama, bahan=:bahan, harga_min=:harga_min, harga_max=:harga_max, satuan=:satuan, deskripsi=:deskripsi, keterangan=:keterangan, stok_tersedia=:stok_tersedia";
        if ($gambar) $sql .= ", gambar='" . $gambar . "'";
        $sql .= " WHERE id=:id";
        $data['id'] = $id;
        $pdo->prepare($sql)->execute($data);
        $id_produk_save = $id;
        flash('ok', 'Produk diperbarui.', 'success');
    } else {
        $data['gambar'] = $gambar ?: 'default.png';
        $sql = "INSERT INTO produk (id_kategori, nama, bahan, harga_min, harga_max, satuan, deskripsi, keterangan, stok_tersedia, gambar) VALUES (:id_kategori, :nama, :bahan, :harga_min, :harga_max, :satuan, :deskripsi, :keterangan, :stok_tersedia, :gambar)";
        $pdo->prepare($sql)->execute($data);
        $id_produk_save = $pdo->lastInsertId();
        flash('ok', 'Produk ditambahkan.', 'success');
    }

    // Multi-upload gambar galeri
    if (!empty($_FILES['galeri']['name'][0])) {
        $files = $_FILES['galeri'];
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) continue;
            if ($files['size'][$i] > 5 * 1024 * 1024) continue;
            $name = 'galeri_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            if (move_uploaded_file($files['tmp_name'][$i], UPLOAD_PATH . $name)) {
                $pdo->prepare("INSERT INTO produk_gambar (id_produk, gambar, urutan) VALUES (?,?,?)")
                    ->execute([$id_produk_save, $name, $i]);
            }
        }
    }
    redirect('produk.php');
}

$edit = null;
if (in_array($aksi, ['edit', 'tambah'])) {
    if ($aksi === 'edit' && $id) {
        $stmt = $pdo->prepare("SELECT * FROM produk WHERE id=?");
        $stmt->execute([$id]);
        $edit = $stmt->fetch();
    } else {
        $edit = ['id'=>0,'id_kategori'=>0,'nama'=>'','bahan'=>'','harga_min'=>'','harga_max'=>'','satuan'=>'pcs','deskripsi'=>'','keterangan'=>'','stok_tersedia'=>1,'gambar'=>'default.png'];
    }
}

$kategori = $pdo->query("SELECT * FROM kategori ORDER BY nama")->fetchAll();

if ($edit !== null): ?>
    <a href="produk.php" class="btn btn-link ps-0 mb-2">← Kembali ke daftar produk</a>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><?= $edit['id'] ? 'Edit' : 'Tambah' ?> Produk</h5>
            <form method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nama Produk *</label>
                        <input type="text" name="nama" value="<?= e($edit['nama']) ?>" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori *</label>
                        <select name="id_kategori" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= (int)$k['id'] ?>" <?= $edit['id_kategori']==$k['id']?'selected':'' ?>><?= e($k['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bahan</label>
                        <input type="text" name="bahan" value="<?= e($edit['bahan']) ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Min *</label>
                        <input type="number" name="harga_min" value="<?= e($edit['harga_min']) ?>" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Max</label>
                        <input type="number" name="harga_max" value="<?= e($edit['harga_max']) ?>" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" value="<?= e($edit['satuan']) ?>" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?= e($edit['deskripsi']) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2"><?= e($edit['keterangan']) ?></textarea>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Gambar Produk (JPG/PNG, max 5MB)</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*" data-preview="prev-img">
                    </div>
                    <div class="col-md-4 text-center">
                        <img id="prev-img" src="<?= gambar_produk($edit['gambar']) ?>" style="max-height:120px; border-radius:8px;">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold"><i class="bi bi-images text-primary"></i> Galeri Foto Tambahan (bisa upload banyak sekaligus)</label>
                        <input type="file" name="galeri[]" class="form-control" accept="image/*" multiple>
                        <div class="form-text">Tahan Ctrl/Shift untuk pilih multi-file. Format JPG/PNG/WEBP, max 5MB per file.</div>
                        <?php
                        if ($edit['id']) {
                            $g = $pdo->prepare("SELECT * FROM produk_gambar WHERE id_produk=? ORDER BY urutan");
                            $g->execute([$edit['id']]);
                            $galeri = $g->fetchAll();
                            if ($galeri): ?>
                                <div class="row g-2 mt-2">
                                    <?php foreach ($galeri as $gi): ?>
                                        <div class="col-4 col-md-2">
                                            <div class="position-relative">
                                                <img src="<?= UPLOAD_URL . e($gi['gambar']) ?>" class="img-fluid rounded" style="aspect-ratio:1; object-fit:cover;">
                                                <a href="?hapus_gambar=<?= (int)$gi['id'] ?>" data-confirm="Hapus foto ini?"
                                                   class="btn btn-danger btn-sm position-absolute" style="top:4px; right:4px; padding:2px 6px;"
                                                   ><i class="bi bi-x"></i></a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                        <?php endif;
                        } ?>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="stok_tersedia" class="form-check-input" id="stk" <?= $edit['stok_tersedia']?'checked':'' ?>>
                            <label class="form-check-label" for="stk">Tersedia / Bisa Dipesan</label>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary mt-3"><i class="bi bi-save"></i> Simpan</button>
            </form>
        </div>
    </div>
<?php else:
    $produk = $pdo->query("SELECT p.*, k.nama AS kategori_nama FROM produk p JOIN kategori k ON k.id=p.id_kategori ORDER BY p.id DESC")->fetchAll();
?>
    <div class="d-flex justify-content-between mb-3">
        <p class="text-muted mb-0">Total: <strong><?= count($produk) ?></strong> produk</p>
        <a href="?aksi=tambah" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Bahan</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach ($produk as $p): ?>
                        <tr>
                            <td><img src="<?= gambar_produk($p['gambar']) ?>" style="width:50px; height:50px; object-fit:cover; border-radius:6px;"></td>
                            <td class="fw-semibold"><?= e($p['nama']) ?></td>
                            <td><?= e($p['kategori_nama']) ?></td>
                            <td><?= e($p['bahan']) ?></td>
                            <td><?= harga_range($p['harga_min'], $p['harga_max'], $p['satuan']) ?></td>
                            <td><?= $p['stok_tersedia'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
                            <td>
                                <a href="?aksi=edit&id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?aksi=hapus&id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-danger" data-confirm="Hapus produk ini?"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
