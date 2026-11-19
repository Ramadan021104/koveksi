<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$valid = false;
$row   = null;

if ($token) {
    $stmt = $pdo->prepare("SELECT rt.*, u.email, u.nama FROM reset_token rt JOIN users u ON u.id=rt.id_user WHERE rt.token=? AND rt.used=0 AND rt.expires_at > NOW()");
    $stmt->execute([$token]);
    $row = $stmt->fetch();
    if ($row) $valid = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid) {
    $p1 = $_POST['password'] ?? '';
    $p2 = $_POST['password2'] ?? '';
    if (strlen($p1) < 6) {
        flash('err', 'Password minimal 6 karakter.', 'danger');
    } elseif ($p1 !== $p2) {
        flash('err', 'Konfirmasi password tidak cocok.', 'danger');
    } else {
        $hash = password_hash($p1, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password=? WHERE id=?")->execute([$hash, $row['id_user']]);
        $pdo->prepare("UPDATE reset_token SET used=1 WHERE id=?")->execute([$row['id']]);
        flash('ok', 'Password berhasil di-reset. Silakan login dengan password baru.', 'success');
        redirect(BASE_URL . 'login.php');
    }
}

$page_title = "Reset Password";
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
    <div class="text-center mb-4">
        <i class="bi bi-key" style="font-size:3rem; color:var(--ik-primary);"></i>
        <h2 class="mt-2">Reset Password</h2>
    </div>

    <?php if (!$valid): ?>
        <div class="alert alert-danger">
            <i class="bi bi-x-circle"></i>
            <strong>Token tidak valid atau sudah kedaluwarsa.</strong><br>
            Silakan request ulang link reset password.
        </div>
        <a href="<?= BASE_URL ?>lupa-password.php" class="btn btn-primary w-100">Request Link Baru</a>
    <?php else: ?>
        <p class="text-muted small">Reset password untuk akun: <strong><?= e($row['email']) ?></strong></p>
        <form method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= e($token) ?>">
            <div class="mb-3">
                <label class="form-label">Password Baru * <span class="text-muted small">(min 6 karakter)</span></label>
                <input type="password" name="password" class="form-control" required minlength="6" autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru *</label>
                <input type="password" name="password2" class="form-control" required minlength="6">
            </div>
            <button class="btn btn-primary w-100"><i class="bi bi-save"></i> Simpan Password Baru</button>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
