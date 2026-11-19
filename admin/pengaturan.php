<?php
$page_title = "Pengaturan Situs";
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("UPDATE pengaturan SET nama_situs=?, wa_admin=?, email_admin=?, alamat=?, tagline=? WHERE id=1")
        ->execute([
            trim($_POST['nama_situs']),
            trim($_POST['wa_admin']),
            trim($_POST['email_admin']),
            trim($_POST['alamat']),
            trim($_POST['tagline']),
        ]);
    flash('ok', 'Pengaturan berhasil disimpan.', 'success');
    redirect('pengaturan.php');
}
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST">
                    <?= csrf_field() ?>
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-gear"></i> Informasi Umum Situs</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Situs</label>
                    <input type="text" name="nama_situs" value="<?= e($pengaturan['nama_situs']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">WhatsApp Admin (format: 628xxx)</label>
                    <input type="text" name="wa_admin" value="<?= e($pengaturan['wa_admin']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Admin</label>
                    <input type="email" name="email_admin" value="<?= e($pengaturan['email_admin']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tagline</label>
                    <input type="text" name="tagline" value="<?= e($pengaturan['tagline']) ?>" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?= e($pengaturan['alamat']) ?></textarea>
                </div>
            </div>
            <button class="btn btn-primary mt-3"><i class="bi bi-save"></i> Simpan Pengaturan</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
