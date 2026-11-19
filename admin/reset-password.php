<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$aid = $_SESSION['otp_verified_admin_id'] ?? 0;
$exp = $_SESSION['otp_verified_admin_expires_at'] ?? 0;

if (!$aid || $exp < time()) {
    flash('err', 'Sesi verifikasi OTP admin kedaluwarsa. Silakan mulai ulang.', 'danger');
    unset($_SESSION['otp_verified_admin_id'], $_SESSION['otp_verified_admin_expires_at']);
    redirect(BASE_URL . 'admin/lupa-password.php');
}

$stmt = $pdo->prepare("SELECT id, username, nama FROM admin WHERE id=?");
$stmt->execute([$aid]);
$a = $stmt->fetch();
if (!$a) {
    flash('err', 'Admin tidak ditemukan.', 'danger');
    redirect(BASE_URL . 'admin/lupa-password.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p1 = $_POST['password'] ?? '';
    $p2 = $_POST['password2'] ?? '';
    if (strlen($p1) < 6) {
        flash('err', 'Password minimal 6 karakter.', 'danger');
    } elseif ($p1 !== $p2) {
        flash('err', 'Konfirmasi password tidak cocok.', 'danger');
    } else {
        $hash = password_hash($p1, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE admin SET password=? WHERE id=?")->execute([$hash, $aid]);
        $pdo->prepare("UPDATE otp_codes SET used=1 WHERE target_type='admin' AND target_id=?")->execute([$aid]);
        unset($_SESSION['otp_verified_admin_id'], $_SESSION['otp_verified_admin_expires_at']);
        flash('ok', 'Password admin berhasil direset. Silakan login dengan password baru.', 'success');
        redirect(BASE_URL . 'admin/login.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Admin - <?= e($pengaturan['nama_situs']) ?></title>
    <link rel="icon" type="image/jpeg" href="<?= LOGO_URL ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <style>body { background: linear-gradient(135deg, #0a2540, #0d4f8a); min-height: 100vh; display: flex; align-items: center; }</style>
</head>
<body>
<div class="container">
    <div class="auth-wrap">
        <div class="text-center mb-4">
            <img src="<?= LOGO_URL ?>" alt="Logo" style="width:100px; height:100px; border-radius:50%; object-fit:cover; box-shadow:0 6px 20px rgba(0,0,0,.25); background:#fff; padding:4px;">
            <h2 class="mt-3">Buat Password Admin Baru</h2>
            <p class="text-muted small mb-0">Akun: <strong><?= e($a['username']) ?></strong> (<?= e($a['nama']) ?>)</p>
        </div>
        <?php show_flash(); ?>

        <div class="alert alert-success small">
            <i class="bi bi-shield-check-fill"></i> OTP terverifikasi. Silakan buat password baru.
        </div>

        <form method="POST" data-testid="form-admin-reset-password">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Password Baru <span class="text-muted small">(min 6 karakter)</span></label>
                <input type="password" name="password" class="form-control" required minlength="6" autofocus data-testid="admin-input-password">
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password2" class="form-control" required minlength="6" data-testid="admin-input-password2">
            </div>
            <button class="btn btn-primary w-100" data-testid="btn-admin-simpan-password">
                <i class="bi bi-save"></i> Simpan Password Baru
            </button>
        </form>
    </div>
</div>
</body>
</html>
