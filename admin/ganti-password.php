<?php
$page_title = "Ganti Password";
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old   = $_POST['old_password'] ?? '';
    $new   = $_POST['new_password'] ?? '';
    $new2  = $_POST['confirm_password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id=?");
    $stmt->execute([$_SESSION['admin_id']]);
    $a = $stmt->fetch();

    if (!$a || !password_verify($old, $a['password'])) {
        flash('err', 'Password lama salah.', 'danger');
    } elseif (strlen($new) < 6) {
        flash('err', 'Password baru minimal 6 karakter.', 'danger');
    } elseif ($new !== $new2) {
        flash('err', 'Konfirmasi password tidak cocok.', 'danger');
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE admin SET password=? WHERE id=?")->execute([$hash, $_SESSION['admin_id']]);
        flash('ok', 'Password berhasil diubah. Silakan login ulang.', 'success');
        unset($_SESSION['admin_id'], $_SESSION['admin_nama']);
        redirect(BASE_URL . 'admin/login.php');
    }
}
?>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-key-fill text-primary"></i> Ganti Password Admin</h5>
                <p class="text-muted small">Ubah password akun admin Anda. Setelah berhasil, Anda akan otomatis logout dan diminta login ulang.</p>
                <form method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Password Lama *</label>
                        <input type="password" name="old_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru * <span class="text-muted small">(minimal 6 karakter)</span></label>
                        <input type="password" name="new_password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru *</label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="6">
                    </div>
                    <button class="btn btn-primary w-100"><i class="bi bi-save"></i> Simpan Password Baru</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
