<?php
$page_title = "Kategori Produk";
require_once __DIR__ . '/includes/header.php';

$aksi = $_GET['aksi'] ?? '';
$id   = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $nama));
    $desk = trim($_POST['deskripsi'] ?? '');
    if ($id) {
        $pdo->prepare("UPDATE kategori SET nama=?, slug=?, deskripsi=? WHERE id=?")->execute([$nama, $slug, $desk, $id]);
        flash('ok', 'Kategori berhasil diperbarui.', 'success');
    } else {
        $pdo->prepare("INSERT INTO kategori (nama,slug,deskripsi) VALUES (?,?,?)")->execute([$nama, $slug, $desk]);
        flash('ok', 'Kategori berhasil ditambahkan.', 'success');
    }
    redirect('kategori.php');
}

if ($aksi === 'hapus' && $id) {
    $pdo->prepare("DELETE FROM kategori WHERE id=?")->execute([$id]);
    flash('ok', 'Kategori dihapus.', 'success');
    redirect('kategori.php');
}

$edit = null;
if ($aksi === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM kategori WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

$list = $pdo->query("SELECT k.*, (SELECT COUNT(*) FROM produk p WHERE p.id_kategori=k.id) AS jml FROM kategori k ORDER BY k.nama")->fetchAll();
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold"><?= $edit ? 'Edit' : 'Tambah' ?> Kategori</h6>
                <form method="POST">
                    <?= csrf_field() ?>
                    <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int)$edit['id'] ?>"><?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" value="<?= e($edit['nama'] ?? '') ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?= e($edit['deskripsi'] ?? '') ?></textarea>
                    </div>
                    <button class="btn btn-primary w-100"><?= $edit ? 'Update' : 'Tambah' ?></button>
                    <?php if ($edit): ?><a href="kategori.php" class="btn btn-link w-100">Batal Edit</a><?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Nama</th><th>Slug</th><th>Jumlah Produk</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php foreach ($list as $k): ?>
                            <tr>
                                <td class="fw-semibold"><?= e($k['nama']) ?></td>
                                <td class="text-muted small"><?= e($k['slug']) ?></td>
                                <td><span class="badge bg-light text-dark"><?= (int)$k['jml'] ?> produk</span></td>
                                <td>
                                    <a href="?aksi=edit&id=<?= (int)$k['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <a href="?aksi=hapus&id=<?= (int)$k['id'] ?>" class="btn btn-sm btn-danger" data-confirm="Hapus kategori ini? Semua produk di dalamnya akan ikut terhapus."><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
