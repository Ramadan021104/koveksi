<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (is_admin()) redirect(BASE_URL . 'admin/index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (is_rate_limited($pdo, 'admin')) {
        flash('err', 'Terlalu banyak percobaan login gagal. Tunggu 15 menit.', 'danger');
    } else {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username=?");
        $stmt->execute([$u]);
        $a = $stmt->fetch();
        if ($a && password_verify($p, $a['password'])) {
            record_login_attempt($pdo, 'admin', true);
            clear_login_attempts($pdo, 'admin');
            $_SESSION['admin_id'] = $a['id'];
            $_SESSION['admin_nama'] = $a['nama'];
            redirect(BASE_URL . 'admin/index.php');
        } else {
            record_login_attempt($pdo, 'admin', false);
            flash('err', 'Username atau password salah.', 'danger');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - <?= e($pengaturan['nama_situs']) ?></title>
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
            <img src="<?= LOGO_URL ?>" alt="Logo <?= e($pengaturan['nama_situs']) ?>" style="width:100px; height:100px; border-radius:50%; object-fit:cover; box-shadow:0 6px 20px rgba(0,0,0,.25); background:#fff; padding:4px;">
            <h2 class="mt-3">Login Admin</h2>
            <p class="text-muted small mb-0"><?= e($pengaturan['nama_situs']) ?></p>
        </div>
        <?php show_flash(); ?>
        <form method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Masuk Panel Admin</button>
        </form>
        <div class="text-center mt-3">
            <a href="<?= BASE_URL ?>" class="small text-muted">← Kembali ke website</a>
        </div>
    </div>
</div>
</body>
</html>
