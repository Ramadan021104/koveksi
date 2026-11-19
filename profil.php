<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$user = user_data();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user['id']]);
$u = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($pass) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET nama=?, no_hp=?, alamat=?, password=? WHERE id=?")
            ->execute([$nama, $no_hp, $alamat, $hash, $user['id']]);
    } else {
        $pdo->prepare("UPDATE users SET nama=?, no_hp=?, alamat=? WHERE id=?")
            ->execute([$nama, $no_hp, $alamat, $user['id']]);
    }
    $_SESSION['user_nama'] = $nama;
    flash('ok', 'Profil berhasil diperbarui.', 'success');
    redirect(BASE_URL . 'profil.php');
}

$page_title = "Profil";
require_once __DIR__ . '/includes/header.php';
?>

<h2 class="section-title">Profil Saya</h2>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST">
                    <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= e($u['nama']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" value="<?= e($u['email']) ?>" class="form-control" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No HP / WhatsApp</label>
                    <input type="text" name="no_hp" value="<?= e($u['no_hp']) ?>" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password Baru <span class="text-muted small">(kosongkan jika tidak diganti)</span></label>
                    <input type="password" name="password" class="form-control" minlength="6">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"><?= e($u['alamat']) ?></textarea>
                </div>
            </div>
            <button class="btn btn-primary mt-3"><i class="bi bi-save"></i> Simpan Perubahan</button>
            <a href="<?= BASE_URL ?>pesanan-saya.php" class="btn btn-outline-primary mt-3">Lihat Pesanan Saya</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
