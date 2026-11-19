<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (is_login()) redirect(BASE_URL . 'index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (is_rate_limited($pdo, 'user')) {
        flash('err', 'Terlalu banyak percobaan login gagal. Tunggu 15 menit.', 'danger');
    } else {
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $u = $stmt->fetch();
        if ($u && $u['password'] && password_verify($pass, $u['password'])) {
            record_login_attempt($pdo, 'user', true);
            clear_login_attempts($pdo, 'user');
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['user_nama'] = $u['nama'];
            $_SESSION['user_email'] = $u['email'];
            flash('ok', 'Login berhasil. Selamat datang, ' . $u['nama'] . '!', 'success');
            redirect(BASE_URL . 'index.php');
        } else {
            record_login_attempt($pdo, 'user', false);
            flash('err', 'Email atau password salah.', 'danger');
        }
    }
}

$google_cfg = require __DIR__ . '/config/google_oauth.php';
$google_active = !empty($google_cfg['client_id']) && !empty($google_cfg['client_secret']);

$page_title = "Login";
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
    <div class="text-center mb-4">
        <img src="<?= LOGO_URL ?>" alt="Logo <?= e($pengaturan['nama_situs']) ?>" style="width:90px; height:90px; border-radius:50%; object-fit:cover; box-shadow:0 4px 16px rgba(13,79,138,.18);">
        <h2 class="mt-3">Login Customer</h2>
        <p class="text-muted small mb-0">Masuk untuk membuat custom order</p>
    </div>

    <?php if ($google_active): ?>
        <a href="<?= BASE_URL ?>auth-google.php" class="btn btn-outline-secondary w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
            <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3c-1.6 4.7-6 8-11.3 8-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.6 16 19 13 24 13c3.1 0 5.8 1.2 7.9 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.3l-6.2-5.2C29.3 35 26.8 36 24 36c-5.3 0-9.7-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.2 4.2-4.1 5.6l6.2 5.2C41 35.9 44 30.4 44 24c0-1.3-.1-2.4-.4-3.5z"/></svg>
            <span class="fw-semibold">Lanjutkan dengan Google</span>
        </a>
        <div class="text-center text-muted small my-2">— atau login dengan email —</div>
    <?php endif; ?>

    <form method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Login</button>
    </form>
    <div class="text-center mt-3 small">
        <a href="<?= BASE_URL ?>lupa-password.php" class="text-decoration-none">Lupa Password?</a>
        <span class="text-muted mx-1">·</span>
        Belum punya akun? <a href="<?= BASE_URL ?>register.php" class="fw-semibold">Daftar di sini</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
